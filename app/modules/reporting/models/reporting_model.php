<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class reporting_model extends MY_Model {
	private $userid;
	public function __construct(){
		parent::__construct();
		$this->userid = user_or_cm();
	}

	function get_groups() {
		$user = $this->model->get("ids,role", USERS, "id = '".session('uid')."'");
		$client = array();
		if (session("cm_uid")) {
			$client = $this->model->get("ids,role", USERS, "id = '".session('cm_uid')."'");
		}
		$this->db->select('grp.*');
		$this->db->from('general_groups grp');
		if($user->role == "customer"){
			$this->db->join('user_group as groups','groups.id_group = grp.ids' );
			$this->db->where('groups.id_user',$user->ids);										
		}elseif($user->role != "admin"){
			if(!session("cm_uid")){
				$this->db->join($user->role.'_group as groups','groups.id_group = grp.ids' );
				$this->db->where('groups.id_user',$user->ids);
			}else{
				$this->db->join('user_group as groups','groups.id_group = grp.ids' );
				$this->db->where('groups.id_user',$client->ids);
			}
		}
		return $this->db->get()->result();
	}
}
