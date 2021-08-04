<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class google_business extends MX_Controller {
	public $table;
	public $module;
	public $module_name;
	public $module_icon;

	public function __construct(){
		parent::__construct();
		$this->table = GOOGLE_BUSINESS_ACCOUNTS;
		$this->module = get_class($this);
		$this->module_name = lang("google_business_accounts");
		$this->module_icon = "fas fa-store";
		$this->load->model($this->module.'_model', 'model');

	}

	public function index(){

	}

	public function block_general_settings(){
		$data = array();
		$this->load->view('account/general_settings', $data);
	}

	public function block_list_account(){
		$data = array(
			'module'       => $this->module,
			'module_name'  => $this->module_name,
			'module_icon'  => $this->module_icon,
			'list_account' => $this->model->fetch("id, ids, pid, username, avatar, status", $this->table, "uid = '".session("uid")."'")
		);
		$this->load->view("account/index", $data);
	}
	
	public function oauth(){
		$google_business = new Google_Business_API();
		redirect($google_business->login_url());
	}

	public function add_account(){
		$google_business = new Google_Business_API();
		$access_token = $google_business->get_access_token();
		// echo "<pre>";var_dump($access_token);



		$locations     = $google_business->get_locations();
		set_session("gb_access_token", $access_token);

		$data = array(
			"locations" => $locations,
		);

		$this->template->build('account/add_account', $data);
	}

	public function ajax_add_account(){
		$accounts = $this->input->post("accounts");
		$access_token = session("gb_access_token");

		if(empty($accounts)){
			ms(array(
	        	"status"  => "error",
	        	"message" => lang('please_select_at_least_one_item')
	        ));
		}

		if($access_token){
			$google_business = new Google_Business_API();
			$google_business->set_access_token($access_token);
			$locations = $google_business->get_locations();

			if(empty($locations)){
				ms(array(
					"status" => "error",
					"message" => lang("Can't found locations on this account")
				));
			}

			foreach ($accounts as $account) {
				if(!check_number_account($this->table)){
					ms(array(
						"status" => "error",
						"message" => lang("limit_social_accounts")
					));
				}

				foreach ($locations as $row) {
					$data = array();
					if($row->getName() == $account){

						$data = array(
							"uid"          => session("uid"),
							"ids"          => ids(),
							"pid"          => $row->getName(),
							"username"     => $row->getLocationName(),
							"avatar"       => "https://ui-avatars.com/api?name=".$row->getLocationName()."&size=128&background=4b88ef&color=fff",
							"access_token" => $access_token,
							"status"       => 1,
							"changed"      => NOW
						);

						$account = $this->model->get("*", $this->table, "pid = '".$row->getName()."' AND uid = '".session("uid")."'");
						if(empty($account)){
							$data["created"] = NOW;
							$this->db->insert($this->table, $data);
						}else{
							$this->db->update($this->table, $data, array("id" => $account->id));
						}
					}
				}
			}

			ms(array(
				"status" => "success",
				"message" => lang('add_account_successfully')
			));
		}else{
			ms(array(
	        	"status"  => "error",
	        	"message" => lang('an_error_occurred_during_processing_please_try_again')
	        ));
		}
	}

	public function ajax_delete_item(){
		$board = $this->model->get("*", $this->table, "ids = '".post("id")."'");
		$this->model->delete($this->table, post("id"), false);
	}
}