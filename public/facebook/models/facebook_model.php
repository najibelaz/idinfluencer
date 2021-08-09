<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class facebook_model extends MY_Model { 
	public function __construct(){
		parent::__construct();
		$this->tb_accounts = "facebook_accounts";
		$this->tb_posts = "facebook_posts";
		include APPPATH."../public/facebook/libraries/facebookapi.php";
		include APPPATH."../public/facebook/libraries/facebookcookie.php";
	}
 
	public function post_validator(){
		$accounts  = get_social_accounts("facebook");
		$caption = post("caption");
		$link = post("link");
		$type = post("type");
		$medias = $this->input->post("media");
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
				if(empty($medias)){
					$errors[] = lang("The images is required");
				}

				break;

			case 'video':
				if(empty($medias)){
					$errors[] = lang("The video is required");
				}
				
				break;
			
			default:
				$errors[] = lang("Please select a type to post");
				break;
		}

		return $errors;
	}

	public function post_handler($ids = array()){
		$typeOfSubmit = isset($_POST['typeOfSubmit']) ? $_POST['typeOfSubmit'] : 'post';
		$issetpid = isset($_GET['pid']) ? true : false;
		$accounts  = get_social_accounts("facebook");
		$type = post("type");
		$caption = post("caption");
		$link = post("link");
		$media = $this->input->post("media");
		$ms = [];
		if($media) {
			foreach ($media as $key => $m) {
				$ms[$m] = $m;
			}
		}
		$media = [];
		foreach ($ms as $key => $m) {
			$media[] = $m;
		}
		$time_post = post("time_post");
		$delay = post("delay");
		$repeat = (int)post("repeat_every");
		$repeat_end = post("repeat_end");
		$type = ($type=="photo" || $type == "video")?"media":$type;
		if(!empty($accounts)){
			if(!post("is_schedule")){
				$status = ($typeOfSubmit == 'draft') ? ST_DRAFT: 2;
				$result_all = array();
				foreach ($accounts as $account_id) {
					$item = $this->model->get("*", $this->tb_accounts, "ids = '".$account_id."' AND uid = '".user_or_cm()."'");
					$account = !empty($groups->id)?$groups->id:"";

					if(!empty($item)){
						$data = array(
							"ids" => ids(),
							"uid" => user_or_cm(),
							"account" => $item->id,
							"group" => $item->pid,
							"category" => $item->type,
							"type" => $type,
							"data" => json_encode(array(
										"media"      => $media,
										"caption"    => $caption,
										"link"       => $link
									)),
							"time_post" => NOW,
							"delay" => 0,
							"time_delete" => 0,
							"changed" => NOW,
							"created" => NOW 
						);

						$fb   = new FacebookAPI();
						$fb->set_access_token($item->access_token);
						$result = $fb->create_post($type, $data, $item->pid, $item->type);

						if(is_string($result) || empty($result)){

							$data['status'] = ST_FAILED;
							$data['result'] = json_encode(array("message" => $result));

							//
							update_setting("fb_post_error_count", get_setting("fb_post_error_count", 0) + 1);
							update_setting("fb_post_count", get_setting("fb_post_count", 0) + 1);
							
							//Save report
							$this->db->insert($this->tb_posts, $data);
						$object = array(
							'text' => sprintf(lang("Content is being unpublished on facebook")),
							'id_user_from' => session("uid"),
							'id_user_to' => user_or_cm(),
							'status' => ST_FAILED,
							'social_label' => 'facebook',
							'idp' => $data['ids'],
						);
						$this->db->insert('notification', $object);
							$result_all[] = array(
					        	"status"  => "error",
					        	"ids"     => $account_id,
					        	"message" => lang("failure")
					        );

						}else{

							//Save report
							update_setting("fb_post_success_count", get_setting("fb_post_success_count", 0) + 1);
							update_setting("fb_post_count", get_setting("fb_post_count", 0) + 1);
							update_setting("fb_post_{$type}_count", get_setting("fb_post_{$type}_count", 0) + 1);

							$data['status'] = $status;
							$data['result'] = json_encode(array("message" => "successfully", "id" => $result->id, "url" => "http://fb.com/".$result->id));

							$this->db->insert($this->tb_posts, $data);
						$object = array(
							'text' => sprintf(lang("Content is being published on facebook")),
							'id_user_from' => session("uid"),
							'id_user_to' => user_or_cm(),
							'status' => ST_PUBLISHED,
							'social_label' => 'facebook',
							'idp' => $data['ids'],
						);
						$this->db->insert('notification', $object);
							$result_all[] = array(
					        	"status"  => "success",
					        	"ids"     => $account_id,
					        	"result"    => lang("successfully")
					        );

						}
					}else{
						$result_all[] = array(
				        	"status"  => "error",
				        	"ids"     => $account_id,
				        	"message" => lang("failure")
				        );
					}
				}

				return $result_all;

			}else{
				$status = ($typeOfSubmit == 'draft') ? ST_DRAFT: 1;
				foreach ($accounts as $key => $account_id) {
					$groups = $this->model->get("*", $this->tb_accounts, "ids = '".$account_id."' AND uid = '".user_or_cm()."'");
					$account = !empty($groups->id)?$groups->id:"";

					$time_post_save = get_to_time($time_post) + $delay*$key;

					if(!empty($groups)){
						$data = array(
							"ids" => ids(),
							"uid" => user_or_cm(),
							"account" => $account,
							"group" => $groups->pid,
							"category" => $groups->type,
							"type" => $type,
							"data" => json_encode(array(
										"media"      => $media,
										"caption"    => $caption,
										"link"       => $link,
										"repeat"     => $repeat*86400, 
										"repeat_end" => get_timezone_system($repeat_end)
									)),
							"time_post" => get_timezone_system($time_post_save),
							"delay" => $delay,
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
							$this->db->where('id',post('idp'));
							$id_post = post('idp');
        					$this->db->update($this->tb_posts,$data);
						}elseif(post('draft')){
							if(post('action')=="draft"){
								unset($data["created"]);
								unset($data["account"]);
								unset($data["group"]);
								unset($data["category"]);
								$data["status"] = ST_DRAFT;
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
							$id_post = $this->db->insert_id();
						$object = array(
							'text' => sprintf(lang("Content successfully scheduled on facebook")),
							'id_user_from' => session("uid"),
							'id_user_to' => user_or_cm(),
							'status' => ST_PLANIFIED,
							'social_label' => 'facebook',
							'idp' => $data['ids'],
						);
						$this->db->insert('notification', $object);								
							$data_shedule = array(
								'id_post' => $id_post,
								'shedule_date' => get_timezone_system($time_post_save),
								'type ' => 'facebook',
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
			"name" => "facebook",
			"icon" => "fa fa-facebook-official",
			"color" => "#4267b2",
			"content" => $this->load->view("../../../public/facebook/views/post/preview", $data, true)
		);
	}
}
