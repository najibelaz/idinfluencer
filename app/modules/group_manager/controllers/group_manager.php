<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class group_manager extends MX_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');
		if(!is_admin() && ! is_responsable()) {
			
			redirect(cn('dashboard'));
		}
	}

	public function index($page = "empty", $ids = ""){

		if(!file_exists(APPPATH."modules/group_manager/views/general/".$page.".php")){
			$page = "general";
		}

		$groups = $this->model->fetch("*", "general_groups", "");
		// $customer = $this->model->fetch("*", "general_users", "role like 'customer'");
		$manager=array();
		if(is_admin()){

			$manager = $this->model->fetch("*", "general_users", "role like 'manager'");
		}else{
			$user = $this->model->get("id,ids,role", USERS, "id = '".session('uid')."'");
			$this->db->select(USERS.".*");
			$this->db->from(USERS);
			$this->db->join('responsable_cm','responsable_cm.id_manager = '.USERS.'.id' );
			$this->db->where('responsable_cm.id_responsable',$user->id);
			$this->db->where(USERS.".role like 'manager'");
			$manager= $this->db->get()->result();
			// echo"<pre>";var_dump($manager);die();
		}

		$group = array();
		$group_in = array();
		
		if($ids != ""){
			// $group = $this->model->get("*", "general_groups", "ids = '{$ids}' AND uid = '".session("uid")."'");
			// $group_in['manager'] = $this->model->get_group_users("manager_group", $ids);
			// $group_in['client'] = $this->model->get_group_users("user_group", $ids);
			
			// var_dump($res);
			$query = $this->db->query("
				SELECT * FROM general_users 
				where  
				id in 
				(
					SELECT customer_cm.id_customer 
						from customer_cm 
						where id_customer=general_users.id and  
						customer_cm.id_manager=$ids
				) or
				id not in (SELECT customer_cm.id_customer from customer_cm ) and
				general_users.role = 'customer' and general_users.status = 1 and  rs <> '' 
				"
			);
			$customer=$query->result_array();
			$group = $this->model->get("*", "general_users", " id = $ids");
			$this->db->select('id_customer')
				->where('id_manager',$ids)
				->from("customer_cm");
			$data_client= $this->db->get()->result_array();
			$group_in['client'] = $data_client;
		}
		$data = array(
			"accounts" => $this->model->get_accounts(),
			"users" => $this->model->get_users(),
			"group" => $group,
			"group_in" => $group_in,
			"customers"=>$customer,
			"managers"=>$manager,
		);

		if (!$this->input->is_ajax_request()) {
			$view = $this->load->view("general/".$page, $data, true);
			$this->template->build('index', array("view" => $view, "groups" => $groups));
		}else{
			$this->load->view("general/".$page, $data);
		}

	}

	public function ajax_save(){
		$ids = post("ids");
		$name = post("name");
		$id = post("id");
		$role = post('role');
		$insert_id = "";


		if(empty($id)){
			ms(array(
				"status" => "error",
				"message" => lang("Select at least one account")
			));
		}
		// if(!$ids){
			// if($name == ""){
			// 	ms(array(
			// 		"status" => "error",
			// 		"message" => lang("Group name is required")
			// 	));
			// }

			// $item = $this->model->get("*", "general_groups", "name = '{$name}'");
			// if(!empty($item)){
			// 	ms(array(
			// 		"status" => "error",
			// 		"message" => lang("Group name already exists")
			// 	));
			// }

			// $data = array(
			// 	"ids" => ids(),
			// 	"uid" => session("uid"),
			// 	"name" => $name,
			// 	"data" => json_encode(array_unique($id)),
			// 	"changed" => NOW,
			// 	"created" => NOW
			// );
			// foreach ($id as $key => $ids_user) {
			// 	$object = array();
			// 	$object['id_user'] = $ids_user;
			// 	$object['id_group'] = $data['ids'];
			// 	$this->db->insert($role.'_group', $object);
			// }
			
			// $this->db->insert("general_groups", $data);
			// $insert_id = $data['ids'];

		// }else{

		// 	if($name == ""){
		// 		ms(array(
		// 			"status" => "error",
		// 			"message" => lang("Group name is required")
		// 		));
		// 	}

		// 	$item = $this->model->get("*", "general_groups", "name = '{$name}' AND ids != '{$ids}'");
		// 	if(!empty($item)){
		// 		ms(array(
		// 			"status" => "error",
		// 			"message" => lang("Group name already exists")
		// 		));
		// 	}

		// 	$data = array(
		// 		"name" => $name,
		// 		"data" => json_encode(array_unique($id)),
		// 		"changed" => NOW,
		// 	);
		// 	$this->db->delete($role.'_group', array("id_group" => $ids));
		// 	foreach ($id as $key => $ids_user) {
		// 		$object = array();
		// 		$object['id_user'] = $ids_user;
		// 		$object['id_group'] = $ids;
		// 		$this->db->insert($role.'_group', $object);
		// 	}
		// 	$this->db->update("general_groups", $data, array("ids" => $ids));
		// }
		if($ids){
			foreach ($id as $key => $ids_user) {
				$this->db->select('id_customer')
				->where("id_customer =".$ids_user)
				->where("id_manager =".post("ids"))
				->from("customer_cm");
				$data_client= $this->db->get()->result_array();
				if($data_client==null){
					$object = array();
					
					$object['id_customer'] = $ids_user;
					$object['id_manager'] = post("ids");
					$this->db->insert('customer_cm', $object);
				}
			}

			$this->db->select('id,id_customer')
				->where("id_manager=".post('ids'))
				->from("customer_cm");
			$data_client= $this->db->get()->result_array();
			if($data_client!=null){
				foreach($data_client as $client){
					if(!in_array($client["id_customer"], $id)){
						$this->db->delete('customer_cm',"id=".  $client["id"]);
					}
				}
			}
		}
		ms(array(
        	"status"  => "success",
        	"message" => lang('update_successfully'),
        	"id" => $insert_id,
        ));
	}

	public function ajax_delete_item(){
		$this->model->delete("general_groups", post("id"), false);
	}
	public function ajax_delete_responsable(){
		if(!empty(get("ids"))) {
			$this->model->delete("general_users", array('ids' => get("ids")), false);
		}
	}
	public function responsable($page = "empty", $ids = ""){

		if(!file_exists(APPPATH."modules/group_manager/views/general/".$page.".php")){
			$page = "general";
		}

		$groups = $this->model->fetch("*", "general_groups", "");
		$resps=array();
		if(is_admin()){

			$resps = $this->model->fetch("*", USERS, "role='responsable'");
		}else{
			$user = $this->model->get("id,ids,role", USERS, "id = '".session('uid')."'");

			$resps = $this->model->fetch("*", USERS, "role='responsable' and id = ".$user->id);

		}
		$manager = $this->model->fetch("*", "general_users", "role like 'manager'");

		$group = array();
		$group_in = array();
		if($ids != ""){
			$group = $this->model->get("*", USERS, "id = $ids ");
			$group_in = $this->model->get_users_group("responsable_group", $ids);
			$this->db->select('id_manager')
				->where('id_responsable',$ids)
				->from("responsable_cm");
			$data_cm= $this->db->get()->result_array();
			$group_in = $data_cm;
		}
		$data = array(
			"accounts" => $this->model->get_accounts(),
			"groups" => $groups,
			"group" => $group,
			"group_in" => $group_in,
			"managers" => $manager

		);
		// var_dump($data);
		if (!$this->input->is_ajax_request()) {
			$view = $this->load->view("general/".$page, $data, true);
			$this->template->build('responsable', array("view" => $view, "resps" => $resps));
		}else{
			$this->load->view("general/".$page, $data);
		}

	}
	public function ajax_save_resp(){
		$ids_user = post("ids");
		$id = post("id");
		$role = post('role');

		// $this->db->delete($role.'_group', array("id_user" => $ids_user));
		// foreach ($id as $key => $ids) {
		// 	$object = array();
		// 	$object['id_user'] = $ids_user;
		// 	$object['id_group'] = $ids;
		// 	$this->db->insert($role.'_group', $object);
		// }
		foreach ($id as $key => $ids_user) {
			// echo "<pre>";var_dump($ids_user, post("ids"));
			$this->db->select('id_manager')
			->where("id_manager =".$ids_user)
			->where("id_responsable =".post("ids"))
			->from("responsable_cm");
			$data_cm= $this->db->get()->result_array();
			if($data_cm==null){
				$object = array();
				
				$object['id_manager'] = $ids_user;
				$object['id_responsable'] = post("ids");
				$this->db->insert('responsable_cm', $object);
			}
		}

		$this->db->select('id,id_manager')
			->where("id_responsable=".post('ids'))
			->from("responsable_cm");
		$data_cm= $this->db->get()->result_array();
		if($data_cm!=null){
			foreach($data_cm as $client){
				if(!in_array($client["id_manager"], $id)){
					$this->db->delete('responsable_cm',"id=".  $client["id"]);
				}
			}
		}
		ms(array(
        	"status"  => "success",
        	"message" => lang('update_successfully'),
        	"id" => $insert_id,
        ));
	}

}