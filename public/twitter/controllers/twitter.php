<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class twitter extends MX_Controller {
	public $table;
	public $module;
	public $module_name;
	public $module_icon;

	public function __construct(){ 
		parent::__construct();
		
		$this->table = TWITTER_ACCOUNTS;
		$this->module = get_class($this);
		$this->module_name = lang("twitter_accountss");
		$this->module_icon = "fa fa-twitter";
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
			'list_account' => $this->model->fetch("id, ids, username, avatar, status, pid", $this->table, "uid = '".$uid."'")
		);
		$this->load->view("account/index", $data);
	}
	
	public function oauth(){
		$tw = new TwitterAPI(CONSUMER_KEY, CONSUMER_SECRET, TRUE);
		redirect($tw->login_url());
	}

	public function add_account(){
		if(get("denied")){
			redirect(cn("account_manager"));
		}

		$tw = new TwitterAPI(CONSUMER_KEY, CONSUMER_SECRET);
		
		$access_token = (object)$tw->get_access_token();
		$img="http://pbs.twimg.com/profile_images/{$access_token->user_id}/7df3h38zabcvjylnyfe3_normal.png";
		// Set up your settings with the keys you get from the dev site
		$settings = array(
			'oauth_access_token' => "1349027113857396740-Jbgh3dYbu5mVAknWECJeOnDjcyPKXA",
			'oauth_access_token_secret' => "KqGXdo1eD4KLC6ewO2QW0yOnvr77o4XPtu5hYGyfMKGN7",
			'consumer_key' => "qv3qAGzfDIjEQaALvGtEiS9hw",
			'consumer_secret' => "QxjCQEoFfuFGRJ41eeeSZ1NOY8g5WL3INzTVlNHtpuidGBcrW7"
		);
		// Chooose the url you want from the docs, this is the users/show
		$url = 'https://api.twitter.com/1.1/users/show.json';
		// The request method, according to the docs, is GET, not POST
		$requestMethod = 'GET';

		// Set up your get string, we're using my screen name here
		$getfield = "?screen_name={$access_token->screen_name}";
		include APPPATH."../public/twitter/libraries/twitterapiexchange.php";
		
		// Create the object
		$twitter = new TwitterAPIExchange($settings);
		// Make the request and get the response into the $json variable
		$json =  $twitter->setGetfield($getfield)
		->buildOauth($url, $requestMethod)
		->performRequest();
		
		// It's json, so decode it into an array
		$result = json_decode($json);

		// Access the profile_image_url element in the array
		$avatar=$result->profile_image_url;
		
		
		$data = array(
			"uid"          => user_or_cm(),
			"ids"          => ids(),
			"pid"          => $access_token->user_id,
			"username"     => $access_token->screen_name,
			"avatar"       => $avatar,
			"access_token" => json_encode($access_token),
			"status"       => 1,
			"changed"      => NOW
		);

		if(!permission($this->module."_enable")){
			redirect(cn("account_manager"));
		}

		$account = $this->model->get("*", $this->table, "pid = '".$access_token->user_id."' AND uid = '".user_or_cm()."'");
		if(empty($account)){
			if(!check_number_account($this->table)){
				redirect(cn("account_manager"));
			}

			$data["created"] = NOW;
			$this->db->insert($this->table, $data);
		}else{
			$this->db->update($this->table, $data, array("id" => $account->id));
		}

		redirect(cn("account_manager"));
	}

	public function ajax_delete_item(){
		$this->model->delete($this->table, post("id"), false);
	}
}