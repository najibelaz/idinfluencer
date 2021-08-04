<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class jeu_concours_model extends MY_Model {
	public $table;
	public $columns;
	public $module_name;
	public $module_icon;

	public function __construct(){
		parent::__construct();
		$this->table       = "jeux_concours";
		$this->module_name = lang("jeu_concours");
		$this->module_icon = "fa ft-game";
		$this->columns = array(
			"id"            => lang("id"),
			"name"          => lang("name"),
			"q1"            => lang("q1"),
			"q2"            => lang("q2"),
			"q3"            => lang("q3"),
			"img"           => lang("img"),
			"description"   => lang("description"),
			"reglement"     => lang("reglement"),
			"date_debut"    => lang("date_debut"),
			"date_fin"      => lang("date_fin"),
			"id_user"       => lang("id_user")
		);
	}
	public function setGagnant($data,$id){
		$this->db->update($this->table, array('id_gagnant' => $data->id), "ids = '".$id."'");
	}
	public function getallgames(){
		$user = $this->model->get("ids,role", USERS, "id = '".user_or_cm()."'");
		$this->db->distinct();
		$this->db->select($this->table.'.*');
		$this->db->from($this->table);
		$this->db->join(USERS.' as users',$this->table.'.id_user = users.id' );
		
		if($user->role == "customer"){
			// $this->db->join('user_group as groups','groups.id_user = users.ids' );
			$this->db->where('users.ids',$user->ids);										
		}elseif($user->role=="manager"){
			$this->db->join('customer_cm','customer_cm.id_customer = users.id' );
			$this->db->where('customer_cm.id_manager',user_or_cm());
		}elseif($user->role=="responsable"){
			$this->db->join('customer_cm','customer_cm.id_customer = users.id' );
			$this->db->join('responsable_cm','responsable_cm.id_manager = customer_cm.id_manager' );
			$this->db->where('responsable_cm.id_responsable',user_or_cm());
		}
		// elseif($user->role != "admin"){
			// if(!session("cm_uid")){
				// $this->db->join('user_group as groups_user','groups_user.id_user = users.ids' );
				// $this->db->join($user->role.'_group as groups','groups.id_group = groups_user.id_group' );
				// if($user->role=="manager" || $user->role=="responsable"){
				// 	$this->db->where('groups.id_user',$user->ids);
				// }

			// }else{
			// 	$this->db->join('user_group as groups','groups.id_group = users.ids' );
			// 	$this->db->where('groups.id_user',$user->ids);
			// }
		// }
		return $this->db->get()->result();
	}
	public function getallparticipant($table){
		$user = $this->model->get("ids,role", USERS, "id = '".user_or_cm()."'");
		$this->db->distinct();
		$this->db->select($table.'.*');
		$this->db->from($table);
		$this->db->join($this->table.' as game',$table.'.id_game = game.ids' );
		$this->db->join(USERS.' as users','game.id_user = users.id' );
		
		if($user->role == "customer"){
			// $this->db->join('user_group as groups','groups.id_user = users.ids' );
			// $this->db->where('groups.id_user',user_or_cm());										
		}elseif($user->role=="manager"){
			$this->db->join('customer_cm','customer_cm.id_customer = users.id' );
			$this->db->where('customer_cm.id_manager',user_or_cm());
		}elseif($user->role=="responsable"){
			
			$this->db->join('customer_cm','customer_cm.id_customer = users.id' );
			$this->db->join('responsable_cm','responsable_cm.id_manager = customer_cm.id_manager' );
			$this->db->where('responsable_cm.id_responsable',user_or_cm());
		}
		// elseif($user->role != "admin"){
			// if(!session("cm_uid")){
			// 	$this->db->join('user_group as groups_user','groups_user.id_user = users.ids' );
			// 	$this->db->join($user->role.'_group as groups','groups.id_group = groups_user.id_group' );
			// }else{
			// 	$this->db->join('user_group as groups','groups.id_group = users.ids' );
			// 	$this->db->where('groups.id_user',$user->ids);
			// }
		// }
		return $this->db->get()->result();
	}
}
