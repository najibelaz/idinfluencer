<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class mailjet_model extends MY_Model {
	public $table;
	public $columns;
	public $module_name;
	public $module_icon;

	public function __construct(){
		parent::__construct();
		$this->table       = "jeux_concours";
		$this->module_name = lang("jeux_concours");
		$this->module_icon = "fa ft-game";
		$this->columns = array(
			"id"            => lang("id"),
			"name"          => lang("name"),
		);
	} 
}
