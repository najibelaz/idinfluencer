<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class account_manager extends MX_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');
	}

	public function index(){
		$data = array();
		$this->template->title('Dashboard');
		if(is_manager() || is_admin() || is_responsable()){
			if(session("cm_uid")){
				$this->template->build('account/index', $data);
			}else{
				$this->template->build('empty');
			}
		} else{
			$this->template->build('account/index', $data);
		}
	}
}