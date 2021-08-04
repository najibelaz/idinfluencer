<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class instagram_model extends MY_Model { 
	public function __construct(){
		parent::__construct();
		$this->tb_accounts = "instagram_accounts";
		$this->tb_posts = "instagram_posts";
		// include APPPATH."../public/instagram/libraries/instagramapi.php";
		// include APPPATH."../public/instagram/helpers/instagram_helper.php";
		include APPPATH."../public/instagram/libraries/newinstagramapi.php";
	}

	public function post_validator(){
		$accounts  = get_social_accounts("instagram");
		$caption   = post("caption");
		$link      = post("link");
		$type      = post("type");
		$media     = $this->input->post("media");
		$errors = array();

		if(empty($accounts)) return $errors = 0;

		switch ($type) {
			case 'text':
				$errors[] = lang("Instagram requires an image or video");

				break;

			case 'link':
				if(empty($media)){
					$errors[] = lang("The images is required");
				}
				break;

			case 'photo':
				if(empty($media)){
					$errors[] = lang("The images is required");
				}

				break;

			case 'video':
				if(empty($media)){
					$errors[] = lang("The images is required");
				}
				
				break;
			
			default:
				$errors[] = lang("Please select a type to post");
				break;
		}

		return $errors;
	}

	public function post_handler(){
		$typeOfSubmit = isset($_POST['typeOfSubmit']) ? $_POST['typeOfSubmit'] : 'post';
		$status = ($typeOfSubmit == 'draft') ? ST_DRAFT: ST_WAITTING;
		$accounts     = get_social_accounts("instagram");
		$media        = $this->input->post("media");
		$time_post    = post("time_post");
		$caption      = post("caption");
		$url          = post("link");
		$comment      = post("comment");
		$type         = post("type");
		$type         = ($type=="video")?"photo":$type;
		$type         = ($url != "")?"photo":$type;
		$type         = count($media) > 1?"carousel":$type;

		if(!empty($accounts)){
			if(!post("is_schedule")){
				$status = ($typeOfSubmit == 'draft') ? ST_DRAFT: 2;
				$result_all = array();
				foreach ($accounts as $account_id) {
					$instagram_account = $this->model->get("*", $this->tb_accounts, "ids = '".$account_id."' AND uid = '".user_or_cm()."' AND status = 1");
					if(!empty($instagram_account)){
						$data = array(
							"ids" => ids(),
							"uid" => user_or_cm(),
							"account" => $instagram_account->id,
							"type" => $type,
							"data" => json_encode(array(
								"media"   => $media,
								"caption" => $caption,
								"comment" => $comment
							)),
							"time_post" => NOW,
							"delay" => 0,
							"time_delete" => 0,
							"changed" => NOW,
							"created" => NOW
						);
						
						// $proxy_data = get_proxy($this->tb_accounts, $instagram_account->proxy, $instagram_account);	
						try {
							$instagram_account_id=$instagram_account->pid;
							$access_token=$instagram_account->password;
							$facebook_app_id=get_option("facebook_app_id");
							$facebook_app_secret=get_option("facebook_app_secret");
							$fb = new newinstagramapi($facebook_app_id, $facebook_app_secret);
							$response=$fb->postPublier($instagram_account_id,$access_token,$media[0],$caption);
							$result = $response;
						} catch (Exception $e) {
							$result = array(
								"status" => "error",
								"message" => $e->getMessage()
							);
						}
						
						if(is_array($result)){
							$data['status'] = 3;
							$data['result'] = $result['message'];

							//Save report
							update_setting("ig_post_error_count", get_setting("ig_post_error_count", 0) + 1);
							update_setting("ig_post_count", get_setting("ig_post_count", 0) + 1);

							$this->db->insert($this->tb_posts, $data);
							$object = array(
								'text' => sprintf(lang("Content is being unpublished on instagram")),
								'id_user_from' => session("uid"),
								'id_user_to' => user_or_cm(),
								'social_label' => 'instagram',
								'status' => ST_FAILED,
								'idp' => $data['ids'],
							);
							$this->db->insert('notification', $object);
							$result_all[] = $result;
						}else{
							$data['status'] = $status;
							$data['result'] = json_encode(array("message" => "successfully", "id" => $result->id, "url" => "https://www.instagram.com/p/".$result->code));

							//Save report
							update_setting("ig_post_success_count", get_setting("ig_post_success_count", 0) + 1);
							update_setting("ig_post_count", get_setting("ig_post_count", 0) + 1);
							update_setting("ig_post_{$type}_count", get_setting("ig_post_{$type}_count", 0) + 1);

							$this->db->insert($this->tb_posts, $data);
							$object = array(
								'text' => sprintf(lang("Content is being published on instagram")),
								'id_user_from' => session("uid"),
								'id_user_to' => user_or_cm(),
								'social_label' => 'instagram',
								'status' => ST_PUBLISHED,
								'idp' => $data['ids'],
							);
							$this->db->insert('notification', $object);
						 	$result_all[] = array(
					        	"status"  => "success",
					        	"message" => lang('post_successfully')
					        );
						}

					}else{ 
						$result_all[] = array(
				        	"status"  => "error",
				        	"message" => lang("instagram_account_not_exists")
				        );
					}
				}

				return $result_all;

			}else{
				$status = ($typeOfSubmit == 'draft') ? ST_DRAFT: 2;
				foreach ($accounts as $account_id) {
					$instagram_account = $this->model->get("id, username, password", $this->tb_accounts, "ids = '".$account_id."' AND uid = '".user_or_cm()."'");
					if(!empty($instagram_account)){
						$data = array(
							"ids" => ids(),
							"uid" => user_or_cm(),
							"account" => $instagram_account->id,
							"type" => $type,
							"data" => json_encode(array(
										"media"   => $media,
										"caption" => $caption,
										"comment" => $comment
									)),
							"time_post" => get_timezone_system($time_post),
							"delay" => 0,
							"time_delete" => 0,
							"status" => $status,
							"changed" => NOW,
							"created" => NOW
						);

						$id_post = 0;
						if(post('update')){
							unset($data["created"]);
							unset($data["account"]);
							unset($data["group"]);
							unset($data["category"]);
							$data["status"] = ST_WAITTING;
							$id_post = post('idp');
							$this->db->where('id',post('idp'));
        					$this->db->update($this->tb_posts,$data);
						}elseif(post('draft')){
							if(post('action')=="draft"){
								unset($data["created"]);
								unset($data["account"]);
								unset($data["group"]);
								unset($data["category"]);
							}else{
								$data["status"] = ST_WAITTING;
							}
							$this->db->where('id',post('idp'));
							$id_post = post('idp');
        					$this->db->update($this->tb_posts,$data);
						}else{
							$now = time(); // or your date as well
							$your_date = strtr($time_post, '/', '-');
							$your_date = strtotime($your_date);
							$datediff =  $your_date - $now;
							$datediff = round($datediff / (60 * 60 * 24));
							if($datediff > 3){
								$data["status"] = ST_WAITTING;
							}else{
								$data["status"] = ST_PLANIFIED;
							}
							$this->db->insert($this->tb_posts, $data);
						$object = array(
							'text' => sprintf(lang("Content successfully scheduled")),
							'id_user_from' => session("uid"),
							'id_user_to' => user_or_cm(),
							'status' => ST_PLANIFIED,
							'idp' => $data['ids'],
						);
						$this->db->insert('notification', $object);								
							$id_post = $this->db->insert_id();
							$data_shedule = array(
								'id_post' => $id_post,
								'shedule_date' => get_timezone_system($time_post),
								'type ' => 'instagram',
							);
							
							$this->db->insert('shedules', $data_shedule);
						}

					}
				}

				return array(
		        	"status"  => "success",
		        	"message" => lang('add_schedule_successfully')
		        );
			}
		}else{

			return array(
	        	"status"  => "error",
	        	"message" => lang("processing_is_error_please_try_again")
	        );

		}
	}

	public function post_previewer($link_info){
		$caption = post("caption");
		$link = post("link");
		$type = post("type");
		$medias = $this->input->post("media");

		$data = array(
			"ajax_load" => true,
			"type" => $type,
			"caption" => $caption,
			"medias" => $medias,
			"link_info" => $link_info
		);

		return array(
			"name" => "instagram",
			"icon" => "fa fa-instagram",
			"color" => "#d62976",
			"content" => $this->load->view("../../../public/instagram/views/post/preview", $data, true)
		);
	}
}