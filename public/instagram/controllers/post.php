<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class post extends MX_Controller {
	public $table;
	public $module;
	public $post;
	public $module_name;
	public $module_icon;

	public function __construct(){
		parent::__construct();

		$this->tb_accounts = INSTAGRAM_ACCOUNTS;
		$this->tb_posts = INSTAGRAM_POSTS;
		$this->module = get_class($this);
		$this->module_name = lang("instagram_accounts");
		$this->module_icon = "fa fa-instagram";
		$this->load->model(get_class($this).'_model', 'model');
	}

	public function index($ids = ""){
		$post = array();
		if($ids != ""){
			$post = $this->model->get("*", $this->tb_posts, "ids = '{$ids}' AND uid = '".user_or_cm()."'");
			if(empty($post)){
				redirect(PATH."facebook/post");
			}
		}

		$data = array(
			'post'         => $post,
			'module'       => $this->module,
			'module_name'  => $this->module_name,
			'module_icon'  => $this->module_icon,
			'accounts'     => $this->model->fetch("id, pid, username, avatar, ids", $this->tb_accounts, "uid = ".user_or_cm()." AND status = 1")
		);
		$this->template->build('post/index', $data);
	}

	public function preview(){
		$data = array();
		$this->load->view('post/preview', $data);
	}

	public function block_report(){
		$data = array();
		$this->load->view('post/block_report', $data);
	}

	public function block_general_settings(){
		$data = array();
		$this->load->view('post/general_settings', $data);
	}

	public function popup_search_media(){
		$this->load->view("post/popup_search_media");
	}

	public function ajax_search_media(){
		$keyword = post("keyword");
		$type    = post("type");
		$data    = array();

		$instagram_account = $this->model->get("*", $this->tb_accounts, "uid = '".user_or_cm()."' AND status = 1", "rand()");
		$proxy_data = get_proxy($this->tb_accounts, $instagram_account->proxy, $instagram_account);	
		if(!empty($instagram_account)){
			try {
				$ig   = new InstagramAPI($instagram_account->username, $instagram_account->password, $proxy_data->use);
				$data["result"] = $ig->search_media($keyword, $type);
			} catch (Exception $e) {
				echo "<div class='alert alert-danger'>".$e->getMessage()."</div>";
			}
		}

		$this->load->view("post/ajax_search_media", $data);
	}

	public function ajax_search_location(){
		$keyword = post("keyword");
		$data    = array();

		$instagram_account = $this->model->get("*", $this->tb_accounts, "uid = '".user_or_cm()."' AND status = 1", "rand()");
		$proxy_data = get_proxy($this->tb_accounts, $instagram_account->proxy, $instagram_account);	
		if(!empty($instagram_account)){
			try {
				$ig   = new InstagramAPI($instagram_account->username, $instagram_account->password, $proxy_data->use);
				$data["result"] = $ig->search_locations($keyword);
			} catch (Exception $e) {
				echo "<div class='alert alert-danger'>".$e->getMessage()."</div>";
			}
		}

		$this->load->view("post/ajax_search_location", $data, false);
	}

	public function ajax_post(){
		$ids       = post("ids");
		$accounts  = $this->input->post("account");
		$media     = $this->input->post("media");
		$type      = post("type");
		$time_post = post("time_post");
		$caption   = post("caption");
		$comment   = post("comment");
		$location  = post("location");
		$repeat = (int)post("repeat_every");
		$repeat_end = post("repeat_end"); 
		$post = array();

		if($ids){
			$post = $this->model->get("*", $this->tb_posts, "ids = '{$ids}' AND uid = '".user_or_cm()."'");
			if(empty($post)){
				ms(array(
		        	"status"  => "error",
		        	"stop"    => true,
		        	"message" => lang('This post does not exist')
		        ));
			}
		}
		
		if(!$accounts){
			ms(array(
	        	"status"  => "error",
	        	"stop"    => true,
	        	"message" => lang('please_select_an_account')
	        ));
		}

		if(!$media && empty($media)){
			ms(array(
	        	"status"  => "error",
	        	"stop"    => true,
	        	"message" => lang('please_select_a_media')
	        ));
		}

		if(!Instagram_Post_Type($type)){
			ms(array(
	        	"status"  => "error",
	        	"stop"    => true,
	        	"message" => lang('please_select_a_post_type')
	        ));
		}

		if(!post("advance")){
			$comment   = "";
		}
		
		if(!post("is_schedule")){
			if(!empty($accounts)){
				foreach ($accounts as $account_id) {
					$instagram_account = $this->model->get("id, username, password, proxy, default_proxy", $this->tb_accounts, "ids = '".$account_id."' AND uid = '".user_or_cm()."' AND status = 1");
					if(!empty($instagram_account)){
						$data = array(
							"ids" => ids(),
							"uid" => user_or_cm(),
							"account" => $instagram_account->id,
							"type" => post("type"),
							"data" => json_encode(array(
										"media"   => $media,
										"caption" => $caption,
										"comment" => $comment,
										"location"=> $location
									)),
							"time_post" => NOW,
							"delay" => 0,
							"time_delete" => 0,
							"changed" => NOW,
							"created" => NOW
						);
						
						$proxy_data = get_proxy($this->tb_accounts, $instagram_account->proxy, $instagram_account);	
						try {
							$ig = new InstagramAPI($instagram_account->username, $instagram_account->password, $proxy_data->use);
							$result = $ig->post($data);
						} catch (Exception $e) {
							ms(array(
								"status" => "error",
								"message" => $e->getMessage()
							));
						}
						
						if(is_array($result)){
							$data['status'] = 3;
							$data['result'] = $result['message'];

							//Save report
							update_setting("ig_post_error_count", get_setting("ig_post_error_count", 0) + 1);
							update_setting("ig_post_count", get_setting("ig_post_count", 0) + 1);

							$this->db->insert($this->tb_posts, $data);

							ms($result);
						}else{
							$data['status'] = 2;
							$data['result'] = json_encode(array("message" => "successfully", "id" => $result->id, "url" => "https://www.instagram.com/p/".$result->code));

							//Save report
							update_setting("ig_post_success_count", get_setting("ig_post_success_count", 0) + 1);
							update_setting("ig_post_count", get_setting("ig_post_count", 0) + 1);
							update_setting("ig_post_{$type}_count", get_setting("ig_post_{$type}_count", 0) + 1);

							$this->db->insert($this->tb_posts, $data);

						 	ms(array(
					        	"status"  => "success",
					        	"message" => lang('post_successfully')
					        ));
						}

					}else{
						ms(array(
				        	"status"  => "error",
				        	"message" => lang("instagram_account_not_exists")
				        ));
					}
				}
			}

			ms(array(
	        	"status"  => "error",
	        	"message" => lang("processing_is_error_please_try_again")
	        ));
		}else{
			if(!empty($accounts)){
				foreach ($accounts as $account_id) {
					$instagram_account = $this->model->get("id, username, password", $this->tb_accounts, "ids = '".$account_id."' AND uid = '".user_or_cm()."'");
					if(!empty($instagram_account)){
						$data = array(
							"uid" => user_or_cm(),
							"account" => $instagram_account->id,
							"type" => post("type"),
							"data" => json_encode(array(
										"media"   => $media,
										"caption" => $caption,
										"comment" => $comment,
										"location"=> $location,
										"repeat"     => $repeat*86400, 
										"repeat_end" => get_timezone_system($repeat_end)
									)),
							"time_post" => get_timezone_system($time_post),
							"delay" => 0,
							"time_delete" => 0,
							"status" => 1,
							"changed" => NOW
						);

						if(empty($post)){
							$data["ids"] = ids();
							$data["created"] = NOW;
							$this->db->insert($this->tb_posts, $data);
						}else{
							$this->db->update($this->tb_posts, $data, array("id" => $post->id));

							ms(array(
					        	"status"  => "success",
					        	"message" => lang('Edit post successfully')
					        ));
						}
					}
				}
			}

			ms(array(
	        	"status"  => "success",
	        	"message" => lang('add_schedule_successfully')
	        ));
		}
	}

	/****************************************/
	/* CRON                                 */
	/* Time cron: once_per_minute           */
	/****************************************/
	public function cron(){
		$schedule_list = $this->db->select('post.*, account.username, account.password, account.proxy, account.default_proxy')
		->from($this->tb_posts." as post")
		->join($this->tb_accounts." as account", "post.account = account.id")
		->where("(post.status = 1 OR post.status = 4) AND post.time_post <= '".NOW."' AND account.status = 1")->limit(5,0)->get()->result();
		// echo"<pre>";var_dump($schedule_list);

		// die();
		$facebook_app_id=get_option("facebook_app_id");
		$facebook_app_secret=get_option("facebook_app_secret");
		$fb = new newinstagramapi($facebook_app_id, $facebook_app_secret);
		$html="
		<p>Log Crone Post Faceboock</p>
		<table>
			<thead>
				<tr>
					<th>Compte</th>
					<th>Sociale</th>
					<th>Caption</th>
					<th>Time</th>
				</tr>
			</thead>
			<tbody>";
			// var_dump($schedule_list);
		if(!empty($schedule_list)){
			$result=$fb->post($schedule_list);
			// var_dump($result);
			if($result){
				$html.=$result;
				$html.="</tbody>
				</table>";
				$this->model->send_email_crone($html);
			}
		}
		echo 'end cron instgram post';
		
	}
	public function cronPost(){
		$facebook_app_id=get_option("facebook_app_id");
		$facebook_app_secret=get_option("facebook_app_secret");
		$fb = new newinstagramapi($facebook_app_id, $facebook_app_secret);
		$fb->postCrone();

	}
	public function cron_old(){
		$schedule_list = $this->db->select('post.*, account.username, account.password, account.proxy, account.default_proxy')
		->from($this->tb_posts." as post")
		->join($this->tb_accounts." as account", "post.account = account.id")
		->where("(post.status = 1 OR post.status = 4) AND post.time_post <= '".NOW."' AND account.status = 1")->limit(5,0)->get()->result();
		echo "<pre>"; var_dump($schedule_list);die();
		if(!empty($schedule_list)){
			foreach ($schedule_list as $key => $schedule) {
				if(!permission("instagram/post", $schedule->uid)){
					$this->db->delete($this->tb_posts, array("uid" => $schedule->uid, "time_post >=" => NOW));
				}

				$proxy_data = get_proxy($this->tb_accounts, $schedule->proxy, $schedule);
				try {
					$ig = new InstagramAPI($schedule->username, $schedule->password, $proxy_data->use);
					$result = $ig->post($schedule);
				} catch (Exception $e) {
					$result = array(
						"status" => "error",
						"message" => $e->getMessage()
					);
				}

				$data = array();
				if(is_array($result) && $result["status"] == "error"){
					$data['status'] = 3;
					$data['result'] = json_encode(array("message" => $result["message"]));

					//
					update_setting("ig_post_error_count", get_setting("ig_post_error_count", 0, $schedule->uid) + 1, $schedule->uid);
					update_setting("ig_post_count", get_setting("ig_post_count", 0, $schedule->uid) + 1, $schedule->uid);
					
					//Save report
					$this->db->update($this->tb_posts, $data, "id = '{$schedule->id}'");

					echo $result["message"]."<br/>";
				}else{
					$schedule_data = $schedule->data;
					if( isset($schedule_data->repeat) 
						&& isset($schedule_data->repeat_end) 
						&& $schedule_data->repeat > 0 
						&& strtotime(NOW) < strtotime($schedule_data->repeat_end)
					){
						$time_post_next = date("Y-m-d H:i:s", get_to_time($schedule->time_post) + $schedule_data->repeat);
						$this->db->insert($this->tb_posts, array(
							"ids" => ids(),
							"uid" => $schedule->uid,
							"account" => $schedule->account,
							"type" => $schedule->type,
							"data" => json_encode($schedule->data),
							"time_post" => $time_post_next,
							"delay" => $schedule->delay,
							"time_delete" => $schedule->time_delete,
							"status" => $schedule->status,
							"changed" => NOW,
							"changed" => NOW
						));
					}

					//Save report
					update_setting("ig_post_success_count", get_setting("ig_post_success_count", 0, $schedule->uid) + 1, $schedule->uid);
					update_setting("ig_post_count", get_setting("ig_post_count", 0, $schedule->uid) + 1, $schedule->uid);
					update_setting("ig_post_{$schedule->type}_count", get_setting("ig_post_{$schedule->type}_count", 0, $schedule->uid) + 1, $schedule->uid);

					$data['status'] = 2;
					$data['result'] = json_encode(array("message" => "successfully", "id" => $result->id, "url" => "https://www.instagram.com/p/".$result->code));
					$this->db->update($this->tb_posts, $data, "id = '{$schedule->id}'");

					echo '<a target=\'_blank\' href=\'https://instagram.com/p/'.$result->code.'\'>'.lang('post_successfully').'</a><br/>';
				}
			}
		}else{
			
		}
	}
	//****************************************/
	//               END CRON                */
	//****************************************/

	/****************************************/
	/*           SCHEDULES POST             */
	/****************************************/
	public function block_schedules_xml($type = ""){
		$template = array(
			"controller" => "instagram",
			"color" => "#d62976",
			"name"  => lang("auto_post"),
			"icon"  => "fa fa-instagram"
		);

		echo Modules::run("schedules/block_schedules_xml", $template, $this->tb_posts, $type);
	}

	public function schedules(){
		echo Modules::run("schedules/schedules", "username", $this->tb_posts, $this->tb_accounts);
	}

	public function ajax_schedules(){
		echo Modules::run("schedules/ajax_schedules", "username", $this->tb_posts, $this->tb_accounts);
	}

	public function ajax_delete_schedules($delete_all = false, $type = ""){
		echo Modules::run("schedules/ajax_delete_schedules", $this->tb_posts, $delete_all, $type);
	}
	//****************************************/
	//         END SCHEDULES POST            */
	//****************************************/
	
}