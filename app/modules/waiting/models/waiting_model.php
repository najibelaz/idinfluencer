<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class waiting_model extends MY_Model {
	public function __construct(){
		parent::__construct();
	}

	function getListt($table, $columns, $limit=-1, $page=-1,$status){
		$user = $this->model->get("ids,role", USERS, "id = '".session('uid')."'");
		$c = (int)get_secure('c'); //Column key
		$t = get_secure('t'); //Sort type
		$k = get_secure('k'); //Search keywork
		$this->db->distinct();
		if($limit == -1){
			$this->db->select('count(*) as sum');
		}else{
			$this->db->select($table.".ids as ids,".$table.".id as id,".$table.".type as type,".$table.".return_reason as return_reason,".$table.".data as data,".$table.".social_label as social_label,".$table.".time_post as time_post");
		}
		
		$this->db->from($table);
		$this->db->join(USERS, USERS.'.id = '.$table.'.uid');
		// if($user->role == "manager" || $user->role == "responsable"){
		// 	$this->db->join('user_group', USERS.'.ids = user_group.id_user');
		// 	$this->db->join('manager_group', 'user_group.id_group = manager_group.id_group');
		// 	if($user->role == "responsable"){
		// 		$this->db->join('responsable_group', 'manager_group.id_group = responsable_group.id_group');
		// 		$this->db->where("responsable_group.id_user ='".$user->ids."'");
		// 	}else{
		// 		$this->db->where("manager_group.id_user ='".$user->ids."'");
		// 	}
		// }
		$this->db->where($table.'.status', $status);
		$this->db->where('time_post >=', '(NOW() + INTERVAL 3 DAY)', false);
		if($limit != -1) {
			$this->db->limit($limit, $page);
		}
		if(session('cm_uid')){
			$this->db->where($table.'.uid',session('cm_uid') );
		}

		if($k){
			$i = 0;
			foreach ($columns as $column_name => $column_title) {
				if($i == 0){
					$this->db->like($column_name, $k);
				}else{
					$this->db->or_like($column_name, $k);
				}
				$i++;
			}
		}

		if($c){
			$i = 0;
			$s = ($t && ($t == "asc" || $t == "desc"))?$t:"desc";
			foreach ($columns as $column_name => $column_title) {
				if($i == $c){
					$this->db->order_by($column_name , $s);
				}
				$i++;
			}
		}else{
			$this->db->order_by($table.'.time_post', 'desc');
		}
				
		$query = $this->db->get();

		if($query->result()){
			if($limit == -1){
				return $query->row()->sum;
			}else{
				$result =  $query->result();
				return $result;
			}

		}else{
			return false;
		}
	}
	function update_status_post($pid,$status,$social,$reason = null){
		$this->db->set('status', $status);
		if($reason != null){
			$this->db->set('return_reason', $reason);
		}
		$this->db->where('id', $pid);
		return $this->db->update($social.'_posts');
	}
}
