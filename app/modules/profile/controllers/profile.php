<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class profile extends MX_Controller {

	private $userid;
	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');
		$this->userid = user_or_cm();
	}

	public function index($page = "general"){

		if(!file_exists(APPPATH."modules/profile/views/".$page.".php")){
			$page = "general";
		}

		$data = array(
			"account" => $this->model->get_profile()
		);
		$user = $this->model->get("*", USERS, "id = '".$this->userid."'");
		$info = $this->model->get("*", "user_information", "id_user = '".$this->userid."'");
		$data['user'] = $user;
		$data['information'] = $info;
		
		if($page == "facture"){
			$result = array();
				$sousc = $this->model->get_souscritpion($user->new_id_teamlead);
				$data['invoices'] = $sousc;
		}
		if (!$this->input->is_ajax_request()) {
			$view = $this->load->view($page, $data, true);
			//modules::run("profile/index", $data);
			$this->template->build('index', array("view" => $view));
		}else{
			$this->load->view($page, $data);
		}

	}
	public function ajax_update_account(){
		$fullname = post("fullname");
		$email    = post("email");
		$timezone = post("timezone");

		if($fullname == ""){
			ms(array(
				"status"  => "error",
				"message" => lang("please_enter_fullname")
			));
		}

		if($email == ""){
			ms(array(
				"status"  => "error",
				"message" => lang("please_enter_email")
			));
		}

		if(!filter_var(post("email"), FILTER_VALIDATE_EMAIL)){
		  	ms(array(
				"status"  => "error",
				"message" => lang("email_address_in_invalid_format")
			));
		}

		//
		$user_check = $this->model->get("id", USERS, "email = '{$email}' AND id != '".session("uid")."'");
		if(!empty($user_check)){
			ms(array(
				"status"  => "error",
				"message" => lang("this_email_already_exists")
			));
		}

		$check_timezone = 0;
		foreach (tz_list() as $key => $value) {
			if($timezone == $value['zone']){
				$check_timezone = 1;
			}
		}

		if(!$check_timezone){
			ms(array(
				"status"  => "error",
				"message" => "Timezone is required"
			));
		}

		$this->db->update(USERS, array(
			"fullname" => $fullname,
			"email" => $email,
			"timezone" => $timezone
		), array("id" => session("uid")));

		ms(array(
			"status"  => "success",
			"message" => lang("successfully")
		));
	}
	public function view_user($ids = "",$url="/"){
		if(is_manager() || is_admin() || is_responsable()){
			$user = $this->model->get("*", USERS, "id = '".$ids."'");
			if(!empty($user)){
				unset_session("cm_uid");
				set_session('cm_uid',$ids);
				
			if ($this->input->is_ajax_request()) {
				ms(array(
					"status" => "success",
					"message" => lang("successfully")
				));
			}else{
				redirect(cn('dashboard'));
			}
			} else{
				ms(array(
					"status" => "error",
					"message" => lang("Unable to find the account to view")
				));
			}
		}else{
			load_404();
		}
			// $this->template->build('show', array("user" => $user));
	}
	public function rm_user($ids = ""){
		if(is_manager() || is_admin() || is_responsable()){
			unset_session("cm_uid");
			ms(array(
					"status" => "success",
					"message" => lang("successfully")
				));
		}else{
			load_404();
		}
	}
	public function ajax_change_password(){
		$password = post("password");
		$confirm_password = post("confirm_password");

		if(strlen($password) < 6){
			ms(array(
				"status"  => "error",
				"message" => lang("password_must_be_greater_than_5_characters")
			));
		}

		if($password != $confirm_password){
			ms(array(
				"status"  => "error",
				"message" => lang("password_does_not_match_the_confirm_password")
			));
		}

		$this->db->update(USERS, array(
			"password" => md5($password)
		), array("id" => session("uid")));

		ms(array(
			"status"  => "success",
			"message" => lang("successfully")
		));
 	}

 	public function ajax_change_package() {
 		$alter_pack = post('alter_pack');
 		$status = 'success';

 		//TODO: send notification 
 		if( $alter_pack == 'change_pack') {
 			$message = lang("votre_demande_de_changement_de_formule_a_bien_ete_envoyé");
 		} elseif ($alter_pack == 'stop_pack') {
 			$message = lang("votre_demande_d_arret_de_formule_a_bien_ete_envoyé");
 		} else {
			$message = lang("erreur_d_envoi");
 			$status = 'error';
 		}
 		ms(array(
			"status"  => $status,
			"message" => $message
		));
 	}
}