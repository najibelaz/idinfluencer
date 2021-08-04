<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class google_business_model extends MY_Model { 
	public function __construct(){
		parent::__construct();
		$this->tb_accounts = "google_business_accounts";
		$this->tb_posts = "google_business_posts";
		include APPPATH."../public/google_business/libraries/google_business_api.php";
	}

	public function post_validator(){
		$accounts  = get_social_accounts("google_business");
		$caption   = post("caption");
		$link      = post("link");
		$type      = post("type");
		$media     = $this->input->post("media");
		$errors = array();

		if(empty($accounts)) return $errors = 0;

		switch ($type) {
			case 'text':
				if($caption == ""){ 
					$errors[] = lang("This caption is required");
				}
				break;

			case 'link':
				if($link == ""){
					$errors[] = lang("The URL is required");
				}

				if (!filter_var($link, FILTER_VALIDATE_URL)) {
					$errors[] = lang("The URL is not a valid");
				}

				if(empty($media)){
					$errors[] = lang("The images is required");
				}

				if($caption == ""){ 
					$errors[] = lang("This caption is required");
				}
				break;

			case 'photo':
				if(empty($media)){
					$errors[] = lang("The images is required");
				}
				break;

			case 'video':
				$errors[] = lang("Google business not support post video");
				break;
			
			default:
				$errors[] = lang("Please select a type to post");
				break;
		}

		return $errors;
	}

	public function post_handler(){
		$accounts 	  = get_social_accounts("google_business");
		$media        = $this->input->post("media");
		$time_post    = post("time_post");
		$caption      = post("caption");
		$url          = post("link");
		$type         = post("type");
		$type         = ($type=="photo")?"media":$type;

		if($type=="link"){
			if(!empty($media)){
				$type = "media";
			}else{
				$type = "text";
			}
		}

		if(!empty($accounts)){
			if(!post("is_schedule")){
				
				$result_all = array();
				foreach ($accounts as $account_id) {
					$gb_account = $this->model->get("id, username, pid, access_token", $this->tb_accounts, "ids = '".$account_id."' AND uid = '".session("uid")."'");
					if(!empty($gb_account)){
						$data = array(
							"ids" => ids(),
							"uid" => session("uid"),
							"account" => $gb_account->id,
							"type" => $type,
							"data" => json_encode(array(
										"media"      => $media,
										"caption"    => $caption,
										"url"        => $url,
										"cta_action" => "LEARN_MORE"
									)),
							"time_post" => NOW,
							"delay" => 0,
							"time_delete" => 0,
							"changed" => NOW,
							"created" => NOW
						);

						$google_business = new Google_Business_API();
						$access_token = $google_business->set_access_token($gb_account->access_token);
						$result = $google_business->post($data, $gb_account->pid);

						if(is_array($result)){
							$data['status'] = 3;
							$data['result'] = $result['message'];

							//Save report
							update_setting("gb_post_error_count", get_setting("gb_post_error_count", 0) + 1);
							update_setting("gb_post_count", get_setting("gb_post_count", 0) + 1);

							$this->db->insert($this->tb_posts, $data);
							$object = array(
								'text' => sprintf(lang("Content is being unpublished on google business")),
								'id_user_from' => session("uid"),
								'id_user_to' => user_or_cm(),
								'social_label' => 'google_business',
								'status' => ST_FAILED,
								'idp' => $data['ids'],
							);
							$this->db->insert('notification', $object);
							$result_all[] = $result;
						}else{
							$data['status'] = 2;
							$data['result'] = json_encode(array("message" => "successfully", "id" => $result, "url" => $result));

							//Save report
							update_setting("gb_post_success_count", get_setting("gb_post_success_count", 0) + 1);
							update_setting("gb_post_count", get_setting("gb_post_count", 0) + 1);
							update_setting("gb_post_{$type}_count", get_setting("gb_post_{$type}_count", 0) + 1);

							$this->db->insert($this->tb_posts, $data);
							$this->db->insert($this->tb_posts, $data);
							$object = array(
								'text' => sprintf(lang("Content is being published on google business")),
								'id_user_from' => session("uid"),
								'id_user_to' => user_or_cm(),
								'social_label' => 'google_business',
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
				        	"message" => lang("This account not exists")
				        );
					}
				}

		        return $result_all;

			}else{
				foreach ($accounts as $account_id) {
					$gb_account = $this->model->get("id, username, pid, access_token", $this->tb_accounts, "ids = '".$account_id."' AND uid = '".session("uid")."'");
					if(!empty($gb_account)){
						$data = array(
							"ids" => ids(),
							"uid" => session("uid"),
							"account" => $gb_account->id,
							"type" => $type,
							"data" => json_encode(array(
										"media"      => $media,
										"caption"    => $caption,
										"url"        => $url,
										"cta_action" => "LEARN_MORE"
									)),
							"time_post" => get_timezone_system($time_post),
							"delay" => 0,
							"time_delete" => 0,
							"status" => 1,
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
								'type ' => 'google_business',
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
			"name" => "google_business",
			"icon" => "fas fa-store",
			"color" => "#4b88ef",
			"content" => $this->load->view("../../../public/google_business/views/post/preview", $data, true)
		);
	}
}