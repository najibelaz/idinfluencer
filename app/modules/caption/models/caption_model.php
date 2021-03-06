<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class caption_model extends MY_Model {
	public function __construct(){
		parent::__construct();
	}

	function getList($table, $columns, $limit=-1, $page=-1){
		$c = (int)get_secure('c'); //Column key
		$t = get_secure('t'); //Sort type
		$k = get_secure('k'); //Search keywork

		if($limit == -1){
			$this->db->select('count(*) as sum');
		}else{
			$this->db->select(implode(", ", array_keys($columns)).", ids");
		}
		
		$this->db->from($table);

		if($limit != -1) {
			$this->db->limit($limit, $page);
		}
		$userid;
		if(session("cm_uid")){
			$userid = session("cm_uid");
		}else{
			$userid = session("uid");
		}
		$this->db->where('uid', $userid);

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
			$this->db->order_by('created', 'desc');
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
}
