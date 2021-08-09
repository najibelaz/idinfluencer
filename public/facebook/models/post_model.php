<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class post_model extends MY_Model {
	public function __construct(){
		parent::__construct();
		$this->tb_accounts = FACEBOOK_ACCOUNTS;
		$this->tb_posts = FACEBOOK_POSTS;
	}

	public function insertShedule($data) {
		var_dump('insertShedule');exit;
		//$this->db->delete('manager_group', array("id_manager" => $ids));
		//$this->db->insert('manager_group', $object);
	}
}
