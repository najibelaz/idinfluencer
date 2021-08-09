<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class users_model extends MY_Model {
	public function __construct(){
		parent::__construct();
	}
	function get_user_res_manger(){
		$user = $this->model->get("*", USERS, "id = '".session('uid')."'");
		$this->db->distinct();
		$this->db->select(USERS.'.*');
		$this->db->from(USERS);
		if($user->role=="responsable"){
			$this->db->join('responsable_cm','responsable_cm.id_manager = '.USERS.'.id' );
			$this->db->where('responsable_cm.id_responsable',$user->id);
		}
		$this->db->where(USERS.'.role',"manager");

		return $this->db->get()->result();
	}
	function getList($table, $columns, $limit=-1, $page=-1, $role=''){
		$c = (int)get_secure('c'); //Column key
		$t = get_secure('t'); //Sort type
		$k = trim(get_secure('k')); //Search keywork

		if($limit == -1){
			$this->db->select('count(*) as sum');
			$this->db->from($table." as users");
		}else{
			$this->db->select(implode(", users.", array_keys($columns)).", users.ids,users.id as ID,users.id as IDUser,users.rs, package.name as package,users.id as user_id");
			$this->db->distinct();
			$this->db->from($table." as users");
			$this->db->join(PACKAGES." as package", 'package.id = users.package', 'left');
			$this->db->join("user_information as user_info", 'user_info.id_user = users.id', 'left');
		}
		
		if($limit != -1) {
			$this->db->limit($limit, $page);
		}

		$this->db->where("users.login_type != 'team'");
		if(empty($role)) {
			$this->db->where("users.role != 'admin'");
			$this->db->where("users.role != 'manager'");
			$this->db->where("users.role != 'responsable'");
		} else {
			if($role != 'customer_inactif') {
				$r = $role;
			} else {
				$r = 'customer';
			}
			$this->db->where("users.role like '".$r."'");
		}


		if($k){
			$i = 0;
			foreach ($columns as $column_name => $column_title) {
				if($i == 0){
					$this->db->like("users.".$column_name, $k);
				}else{
					$this->db->or_like("users.".$column_name, $k);
				}

				$this->db->not_like("users.login_type", "team");
				$i++;
			}
		}

		if($c){
			$i = 0;
			$s = ($t && ($t == "asc" || $t == "desc"))?$t:"desc";
			foreach ($columns as $column_name => $column_title) {
				if($i == $c){
					$this->db->order_by("users.".$column_name , $s);
				}
				$i++;
			}
		}else{
			$this->db->order_by('users.created', 'desc');
		}
		if($role == 'customer_inactif') {
			$this->db->where('users.status = 0');
		} elseif($role == 'customer') {
			$this->db->where('users.status = 1');
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

	public function total_register_by_day($days = 30){
		$days =  date("Y-m-d 00:00:00", strtotime(NOW) - $days*86400);
		$query = $this->db->query("SELECT created FROM ".USERS." WHERE created > '".$days."'");
		return $query->num_rows();
	}

	public function get_login_type(){
		$query = $this->db->query("SELECT COUNT(login_type) as count, login_type FROM ".USERS." GROUP BY login_type");
		$data = array(
			"direct" => 0,
			"facebook" => 0,
			"google" => 0,
			"twitter" => 0
		);
		if($query->result()){
			foreach ($query->result() as $row) {
				$data[$row->login_type] = $row->count;
			}
		}
		
		return (object)$data;
	}
	
	public function get_users_status(){
		$query = $this->db->query("SELECT COUNT(status) as count, status FROM ".USERS." GROUP BY status");
		$data = array(
			"enable" => 0,
			"disable" => 0
		);
		
		if($query->result()){
			foreach ($query->result() as $row) {
				if($row->status == 0){
					$data["disable"] = $row->count;
				}else{
					$data["enable"] = $row->count;
				}
			}
		}
		
		return (object)$data;
	}
	
	public function get_stats($day = 10){
		$day -= 1;
		$table = USERS;
		$value_string = "";
		$date_string = "";
		
		$date_list = array();
		$date = strtotime(date('Y-m-d', strtotime(NOW)));
		for ($i=$day; $i >= 0; $i--) { 
			$left_date = $date - 86400 * $i;
			$date_list[date('Y-m-d', $left_date)] = 0;
		}
		
		//Get data
		$query = $this->db->query("SELECT COUNT(created) as count, DATE(created) as created FROM ".$table." WHERE created > NOW() - INTERVAL 30 DAY GROUP BY DATE(".$table.".created);");
		
		if($query->result()){
			
			foreach ($query->result() as $key => $value) {
				if(isset($date_list[$value->created])){
					$date_list[$value->created] = $value->count;
				}
			}
			
			
		}
		
		foreach ($date_list as $date => $value) {
			$value_string .= "{$value},";
			$date_string .= "'{$date}',";
		}
		
		$value_string = "[".substr($value_string, 0, -1)."]";
		$date_string  = "[".substr($date_string, 0, -1)."]";
		
		return (object)array(
			"value" => $value_string,
			"date" => $date_string
		);
	}
	
	public function getUSer($ids){
		$query = $this->db->query("SELECT * , us.id as ID  FROM `general_users` us left join user_information usi on usi.id_user =us.id WHERE `ids` = '{$ids}' ");
		return $query->row();
		
	}
	
	public function insertGroupes($groupes, $ids) {
		$this->db->delete('manager_group', array("id_user" => $ids));
		if(count($groupes) > 0) {
			foreach ($groupes as $key => $grp) {
				$object = array();
				$sql[] = '('.$grp.', '.$ids.')';
				$object['id_user'] = $ids;
				$object['id_group'] = $grp;
				$this->db->insert('manager_group', $object);
			}
		}
		
	}
	public function get_customer_managers($ids){
		$this->db->distinct();
		$this->db->select('fullname');
		// $this->db->from("user_group customer");
		// $this->db->join("manager_group manager", 'customer.id_group = manager.id_group');
		// $this->db->join(USERS.' users','users.ids = manager.id_user');
		// $this->db->where("customer.id_user='{$ids}'");
		
		$this->db->from(USERS);
		$this->db->join('customer_cm','customer_cm.id_manager = '.USERS.'.id' );
		$this->db->where('customer_cm.id_customer',$ids);
		$q = $this->db->get();
		return $q->result();
	} 
	public function get_manager_resps($ids){
		$this->db->distinct();
		$this->db->select('fullname');
		
		$this->db->from(USERS);
		$this->db->join('responsable_cm','responsable_cm.id_responsable = '.USERS.'.id' );
		$this->db->where('responsable_cm.id_manager',$ids);

		// $this->db->from("manager_group customer");
		// $this->db->join("responsable_group responsable", 'customer.id_group = responsable.id_group');
		// $this->db->join(USERS.' users','users.ids = responsable.id_user');
		$q = $this->db->get();
		return $q->result();
	} 
}
