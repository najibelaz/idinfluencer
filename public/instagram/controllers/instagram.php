<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class instagram extends MX_Controller {
	public $table;
	public $module;
	public $module_name;
	public $module_icon;
	public $username;
	public $password;
	public $security_code;
	public $apiPath;
	public $choice = 1;

	public function __construct(){ 
		parent::__construct();
		
		$this->table = INSTAGRAM_ACCOUNTS;
		$this->module = get_class($this);
		$this->module_name = lang("instagram_accounts");
		$this->module_icon = "fa fa-instagram";
		$this->load->model($this->module.'_model', 'model');
	}

	public function block_general_settings(){
		$data = array();
		$this->load->view('account/general_settings', $data);
	}

	public function block_list_account(){
		$uid = user_or_cm();
		$data = array(
			'module'       => $this->module,
			'module_name'  => $this->module_name,
			'module_icon'  => $this->module_icon,
			'list_account' => $this->model->fetch("id, username, avatar, ids, status", $this->table, "uid = '".$uid."'"),
		);
		$this->load->view("account/index", $data);
	}

	public function block_list_account_google(){
		$uid = user_or_cm();

		$this->table = 'google_accounts';
		$data = array(
			'module'       => $this->module,
			'module_name'  => $this->module_name,
			'module_icon'  => $this->module_icon,
			'list_account' => $this->model->fetch("id, username, avatar, ids, status", $this->table, "uid = '".$uid."'")
		);
		$this->load->view("account/index_google", $data);
	}
	
	public function popup_add_account(){
		$facebook_app_id=get_option("facebook_app_id");
		$facebook_app_secret=get_option("facebook_app_secret");
		$fb = new newinstagramapi($facebook_app_id, $facebook_app_secret);
		$ids = segment(3);
		$result = $this->model->get("*", $this->table, "ids = '".$ids."' AND uid = '".user_or_cm()."'");

		$data = array(
			'module'       => $this->module,
			'module_name'  => $this->module_name,
			'module_icon'  => $this->module_icon,
			'result'       => $result,
			'url'=>$fb->login_url()
		);
		$this->load->view('account/popup_add_account', $data);
	}
	public function add_account(){
		// var_dump($_GET["code"]);
		$facebook_app_id=get_option("facebook_app_id");
		$facebook_app_secret=get_option("facebook_app_secret");
		$fb = new newinstagramapi($facebook_app_id, $facebook_app_secret);
		if(isset($_GET["code"])){
			$access_token = $fb->shortaccessToken();
            // var_dump($accessToken);

			// $token="EAAEL3fGVu7ABAHkKCv056G0mqx4qAxZBrljKzvWcLgWrpvarG76mDXWaZCeEJoRVyndJi8sypAz0NWP2rVqvmkBvjRfx7DhQc99bgNjZAAoysEGsNcGTpkMdbRepJvzxNiCc2bD04vBpSqzlszV1P10LH0iOyLB5BlbucNtZAgpZBKZCVqZAhQvEd3GhV0fFitqN16LMQDOvMueEctS5HP0";
			$user=$fb->getAccountInfo($access_token);
			if(!empty($user)){
				$data = array(
					"uid"      => user_or_cm(),
					"pid"      => $user["id"],
					"avatar"   => $user["profile_picture_url"],
					"username" => $user["username"],
					"password" => $user["access_token"],
					"proxy"    => "",
					"default_proxy" => "",
					"status"   => 1,
					"changed"  => NOW,
				);
			}
			$result = $this->model->get("*", $this->table, "pid = '".$user["id"]."'");
			if(empty($result)){
				$data['ids'] = ids();
				$data['created'] = NOW;
				$this->db->insert($this->table, $data);
			}else{
				$this->db->update($this->table, $data, "id = '".$result->id."'");			
			}
	
		}
		redirect("account_manager");
	}
	public function popup_add_account_google(){
		$ids = segment(3);
		$this->table = 'google_accounts';
		$result = $this->model->get("*", $this->table, "ids = '".$ids."' AND uid = '".user_or_cm()."'");

		$data = array(
			'module'       => $this->module,
			'module_name'  => 'google',
			'module_icon'  => 'fa fa-google',
			'result'       => $result
		);
		$this->load->view('account/popup_add_account_google', $data);
	}
	

	public function ajax_add_account(){
		$username = post("username");
		$password = post("password");
		$proxy    = post("proxy");
		$code     = post("code");
		$security_code     = post("security_code");
		$verification_code = post("code");
		$password_encode = encrypt_encode($password);

		$this->username = $username;
		$this->password = $password;
		$this->security_code = $security_code;

		if(empty($username) || empty($password)){
			ms(array(
				"status"  => "error",
				"message" => lang("please_enter_username_and_password")
			));
		}

		if(!permission("instagram_enable")){
			ms(array(
				"status" => "error",
				"message" => lang("disable_feature")
			));
		}

		$instagram_account = $this->model->get("id, default_proxy", $this->table, "username = '".$username."' AND uid = '".user_or_cm()."'");
		$proxy_data = get_proxy($this->table, $proxy, $instagram_account);


		try {
			$ig = new InstagramAPI($username, $password_encode, $proxy_data->use, true, $security_code, $verification_code);
			$login_repsonse = $ig->login();
			if($login_repsonse["status"] == "success"){
				
				$user = $ig->get_current_user();
				// echo "<pre>";var_dump($user);die();
				if(!empty($user)){
					$user = $user->user;
					
					$data = array(
						"uid"      => user_or_cm(),
						"pid"      => $user->pk,
						"avatar"   => $user->hd_profile_pic_versions["0"]->url,
						"username" => $user->username,
						"password" => $password_encode,
						"proxy"    => (get_option('user_proxy', 1) == 1)?$proxy:"",
						"default_proxy" => $proxy_data->system,
						"status"   => 1,
						"changed"  => NOW,
					);
					if(empty($instagram_account)){

						if(!check_number_account($this->table)){
							ms(array(
								"status" => "error",
								"message" => lang("limit_social_accounts")
							));
						}

						$data['ids'] = ids();
						$data['created'] = NOW;
						$this->db->insert($this->table, $data);
					}else{
						$this->db->update($this->table, $data, "id = '".$instagram_account->id."'");			
					}

					ms(array(
						"status"  => "success",
						"message" => lang("successfully")
					));
				}else{
					ms(array(
						"status"  => "error",
						"message" => lang("login_failed_please_try_again")
					));
				}

			}else{
				ms($login_repsonse);
			}
			
			
		} catch (Exception $e) {

			ms(array(
				"status"  => "error",
				"message" => $e->getMessage()
			));

		}
		
	}

	public function ajax_add_account_google(){
		$username = post("username");
		$password = post("password");
		$code     = post("code");
		$security_code     = post("security_code");
		$verification_code = post("code");
		$password_encode = encrypt_encode($password);

		$this->username = $username;
		$this->password = $password;
		$this->security_code = $security_code;

		if(empty($username) || empty($password)){
			ms(array(
				"status"  => "error",
				"message" => lang("please_enter_username_and_password")
			));
		}

		/*if(!permission("instagram_enable")){
			ms(array(
				"status" => "error",
				"message" => lang("disable_feature")
			));
		}*/
		$this->table = 'google_accounts';
		//$google_account = $this->model->get("id, default_proxy", $this->table, "username = '".$username."' AND uid = '".session("uid")."'");
		//$proxy_data = get_proxy($this->table, $proxy, $instagram_account);


		$data = array(
			"uid"      => user_or_cm(),
			"pid"      => 1,
			"avatar"   => "https://avatar.stackposts.com/?u=DAVID",
			"username" => $username,
			"password" => $password,
			"proxy"    => (get_option('user_proxy', 1) == 1)?$proxy:"",
			"default_proxy" => '',
			"status"   => 1,
			"changed"  => NOW,
		);

		if(!check_number_account($this->table)){
				ms(array(
					"status" => "error",
					"message" => lang("limit_social_accounts")
				));
		}

		$data['ids'] = ids();
		$data['created'] = NOW;
		$this->db->insert($this->table, $data);

		/*if(empty($google_account)){

			if(!check_number_account($this->table)){
				ms(array(
					"status" => "error",
					"message" => lang("limit_social_accounts")
				));
			}

			$data['ids'] = ids();
			$data['created'] = NOW;
			$this->db->insert($this->table, $data);
		} else{
			$this->db->update($this->table, $data, "id = '".$google_account->id."'");			
		}*/
		ms(array(
			"status"  => "success",
			"message" => lang("successfully")
		));

		/*try {
			$ig = new InstagramAPI($username, $password_encode, $proxy_data->use, true, $security_code, $verification_code);
			$login_repsonse = $ig->login();

			if($login_repsonse["status"] == "success"){

				$user = $ig->get_current_user();

				if(!empty($user)){
					$user = $user->user;
					
					$data = array(
						"uid"      => user_or_cm(),
						"pid"      => $user->pk,
						"avatar"   => "https://avatar.stackposts.com/?u=".$user->username,
						"username" => $user->username,
						"password" => $password_encode,
						"proxy"    => (get_option('user_proxy', 1) == 1)?$proxy:"",
						"default_proxy" => $proxy_data->system,
						"status"   => 1,
						"changed"  => NOW,
					);

					if(empty($instagram_account)){

						if(!check_number_account($this->table)){
							ms(array(
								"status" => "error",
								"message" => lang("limit_social_accounts")
							));
						}

						$data['ids'] = ids();
						$data['created'] = NOW;
						$this->db->insert($this->table, $data);
					}else{
						$this->db->update($this->table, $data, "id = '".$instagram_account->id."'");			
					}

					ms(array(
						"status"  => "success",
						"message" => lang("successfully")
					));
				}else{
					ms(array(
						"status"  => "error",
						"message" => lang("login_failed_please_try_again")
					));
				}

			}else{
				ms($login_repsonse);
			}
			
			
		} catch (Exception $e) {

			ms(array(
				"status"  => "error",
				"message" => $e->getMessage()
			));

		}*/
		
	}

	public function clear_cookies(){
		$id = post("id");
		$account = $this->model->get("*", $this->table, "ids = '".post("id")."' AND uid = '".user_or_cm()."'");

		if(!empty($account)){
			$this->db->update("instagram_sessions", array("cookies" => NULL), array("username" => $account->username));
		}

		ms(array(
			"status"  => "success",
			"message" => lang("successfully")
		));
	}

	public function ajax_delete_item(){
		$item = $this->model->get("username", $this->table, "ids = '".post("id")."'");
		if(!empty($item)){
			$this->db->delete('instagram_sessions', "username = '{$item->username}'");
		}
		$this->model->delete($this->table, post("id"), false);

	}

	public function ajax_delete_item_google(){
		$this->table = 'google_accounts';
		$item = $this->model->get("username", $this->table, "ids = '".post("id")."'");
		if(!empty($item)){
			$this->db->delete('google_accounts', "username = '{$item->username}'");
		}
		ms(array(
			"status"  => "success",
			"message" => lang("successfully")
		));
		//$this->model->delete($this->table, post("id"), false);

	}
}