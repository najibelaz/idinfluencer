<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class blocks_model extends MY_Model {
	private $userid;
	public function __construct(){
		parent::__construct();
		$this->userid = user_or_cm();

	}

	function getList($limit=-1, $page=-1){
		if($limit == -1){
			$this->db->select('count(*) as sum');
		}else{
			$this->db->select('*');
		}
		
		$this->db->from(INSTAGRAM_ACCOUNT_TB);

		if($limit != -1) {
			$this->db->limit($limit,$page);
		}

		$this->db->where("uid = '".$this->userid."'");

		$this->db->order_by('created','desc');
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
