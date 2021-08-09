<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class blocks extends MX_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');
	}

	public function set_language(){
		set_language(post("id"));

		ms(array("status" => "success"));
	}

	public function header(){
		$user = array();
		//if(get_option('show_subscription', 0)==1){
			$user = $this->model->get("*", USERS, "id = '".session("uid")."'");	
		//}
		$date = new DateTime($user->expiration_date);
		$expiration_date = $date->format('d/m/Y');

		
		$pack = $this->model->get("*", PACKAGES, "id = '".$user->package."'");
		$namepack = "";
		if ($pack) {
			$namepack = $pack->name;
		}
		$data = array(
			"languages" => $this->model->fetch("*", LANGUAGE_LIST, "status = 1"),
			"user" => $user,
			"expiration_date" => $expiration_date,	
			"package_name" => $namepack
		);
		if(session('cm_uid')){
			$data['chosenuser'] = $user = $this->model->get("* , ".USERS.".id as idu", USERS, USERS.".id = '".session('cm_uid')."'");	
		}
		$this->load->view('header', $data);
	}
	
	public function sidebar(){
		$data = array();
		if(is_manager() || is_admin() || is_responsable()){
			$data['customers'] = getCustomers_sidebar();
			if(session('cm_uid')){
				$data['chosenuser'] = $user = $this->model->get("* , ".USERS.".id as idu", USERS, USERS.".id = '".session('cm_uid')."'");	
			}
		}
		$this->load->view('sidebar', $data);
	}
}