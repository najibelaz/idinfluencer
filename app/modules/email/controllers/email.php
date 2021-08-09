<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class email extends MX_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');
	}

	public function template(){
		$data= array();
		$this->load->view("template", $data);
	}

	public function template_game(){
		$data= array();
		$this->load->view("template_game", $data);
	}

	public function template_activation(){
		$data= array();
		$this->load->view("template_activation", $data);
	}

}