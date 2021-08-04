<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class twitter_model extends MY_Model { 
	public function __construct(){
		parent::__construct();
		$this->tb_accounts = "twitter_accounts";
		$this->tb_posts = "twitter_posts";
		include APPPATH."../public/twitter/libraries/twitterapi.php";
	}

	public function post_validator(){
		$accounts  = get_social_accounts("twitter");
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
				break;

			case 'photo':
				if(empty($media)){
					$errors[] = lang("The images is required");
				}

				break;

			case 'video':
				if(empty($media)){
					$errors[] = lang("The video is required");
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
		
		$accounts  = get_social_accounts("twitter");
		$media     = $this->input->post("media");
		$type      = post("type");
		$time_post = post("time_post");
		$caption   = post("caption");
		$link      = post("link");

		if($type == "link"){
			$caption   = $link != ""?$caption." ".$link:$caption;
		}
		
		$type      = ($type=="link")?"text":$type;

		if(!empty($accounts)){
			if(!post("is_schedule")){
				$status = ($typeOfSubmit == 'draft') ? ST_DRAFT: 2;
				$result_all = array();
				foreach ($accounts as $account_id) {
					$twitter_account = $this->model->get("id, access_token", $this->tb_accounts, "ids = '".$account_id."' AND uid = '".user_or_cm()."' AND status = 1");
					if(!empty($twitter_account)){
						$data = array(
							"ids" => ids(),
							"uid" => user_or_cm(),
							"account" => $twitter_account->id,
							"type" => $type,
							"data" => json_encode(array(
										"media"   => $media,
										"caption" => $caption
									)),
							"time_post" => NOW,
							"delay" => 0,
							"time_delete" => 0,
							"changed" => NOW,
							"created" => NOW
						);

						$tw = new TwitterAPI( get_option("twitter_app_id", ""), get_option("twitter_app_secret", "") );
						$tw->set_access_token($twitter_account->access_token);
						$result = $tw->post($data);
						if(is_array($result)){
							$data['status'] = 3;
							$data['result'] = $result['message'];

							//Save report
							update_setting("tw_post_error_count", get_setting("tw_post_error_count", 0) + 1);
							update_setting("tw_post_count", get_setting("tw_post_count", 0) + 1);

							$this->db->insert($this->tb_posts, $data);
							$object = array(
								'text' => sprintf(lang("Content is being unpublished on twitter")),
								'id_user_from' => session("uid"),
								'id_user_to' => user_or_cm(),
								'social_label' => 'twitter',
								'status' => ST_FAILED,
								'idp' => $data['ids'],
							);
							$this->db->insert('notification', $object);
							$result_all[] = $result;
						}else{
							$data['status'] = $status;
							$data['result'] = json_encode(array("message" => "successfully", "id" => $result, "url" => "https://twitter.com/statuses/".$result));

							//Save report
							update_setting("tw_post_success_count", get_setting("tw_post_success_count", 0) + 1);
							update_setting("tw_post_count", get_setting("tw_post_count", 0) + 1);
							update_setting("tw_post_{$type}_count", get_setting("tw_post_{$type}_count", 0) + 1);

							$this->db->insert($this->tb_posts, $data);
							$object = array(
								'text' => sprintf(lang("Content is being published on twitter")),
								'id_user_from' => session("uid"),
								'id_user_to' => user_or_cm(),
								'social_label' => 'twitter',
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
				        	"message" => lang('twitter_account_not_exists')
				        );
					}
				}

				return $result_all;

			}else{
				$status = ($typeOfSubmit == 'draft') ? ST_DRAFT: 1;
				foreach ($accounts as $account_id) {
					$twitter_account = $this->model->get("id, access_token", $this->tb_accounts, "ids = '".$account_id."' AND uid = '".user_or_cm()."'");
					if(!empty($twitter_account)){
						$data = array(
							"ids" => ids(),
							"uid" => user_or_cm(),
							"account" => $twitter_account->id,
							"type" => $type,
							"data" => json_encode(array(
									"media"   => $media,
									"caption" => $caption
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
							}
							$this->db->insert($this->tb_posts, $data);
							$object = array(
								'text' => sprintf(lang("Content successfully scheduled")),
								'id_user_from' => session("uid"),
								'id_user_to' => user_or_cm(),
								'social_label' => 'twitter',
								'status' => ST_PLANIFIED,
								'idp' => $data['ids'],
							);
							$this->db->insert('notification', $object);							
							$id_post = $this->db->insert_id();
							$data_shedule = array(
								'id_post' => $id_post,
								'shedule_date' => get_timezone_system($time_post),
								'type ' => 'twitter',
							);
							
							$this->db->insert('shedules', $data_shedule);
						}
					}
				}
			}

			return array(
	        	"status"  => "success",
	        	"message" => lang('add_schedule_successfully')
	        );
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
			"name" => "twitter",
			"icon" => "fa fa-twitter",
			"color" => "#00aced",
			"content" => $this->load->view("../../../public/twitter/views/post/preview", $data, true)
		);
	}
}
