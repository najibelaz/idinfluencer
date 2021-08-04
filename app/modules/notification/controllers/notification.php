<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class notification extends MX_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');
	}

	public function get(){
		$data = array(
			"user"    => $this->model->get("admin", USERS, "id = '".session("uid")."'")
		);
		$this->load->view("get_notification", $data);
	}

	public function check_update(){
		$url = "http://api.stackposts.com/scripts/json_products";
	    $result = $this->curl($url);
		$purchases = $this->model->fetch("*", PURCHASE);
		$data = array(
			"purchases" => $purchases,
			"result"  => json_decode($result)
			
		);
		$this->load->view("check_update", $data, false);
	}

	public function curl($url){
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_VERBOSE, 1);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_AUTOREFERER, false);
	    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    $result = curl_exec($ch);
	    curl_close($ch);

	    return $result;
	}
	public function seen($id){
		$user = $this->model->get("ids,role", USERS, "id = '".session('uid')."'");
		$data['read_'.$user->role] = 1;
		$this->db->update("notification", $data, array("id" => $id));
		ms(array(
			"status"  => "success",
			"message" => "",
		));
	}
	public function mark_all_read($id = null){
		$user = $this->model->get("ids,role", USERS, "id = '".session('uid')."'");
		$data['read_'.$user->role] = 1;
		// if($user->role == "admin"){
		// 	$this->db->update("notification", $data, array('read_admin' => 0));
		// }else{
		// 	$this->db->update("notification", $data, array("id_user_to" => session('uid')));
		// }

		// $user = $this->model->get("ids,role", USERS, "id = '".session('uid')."'");
		$query= "";
		if($user->role == "customer"){
			$query = "Select notification.* from notification , ".USERS." user Where user.id = notification.id_user_to and user.id = '".session('uid')."' order by id desc";
			
		} elseif($user->role == "admin") {
			$query = "Select notification.* from notification , ".USERS." user Where user.id = notification.id_user_to and notification.id_user_from !=".session('uid')." order by id desc";
		}  elseif($user->role == "manager") {
			$query = "SELECT notification.* 
						from notification 
						inner join general_users as user on user.id = notification.id_user_to 
						inner join customer_cm on customer_cm.id_customer = user.id 
						where notification.id_user_from !=".session('uid')." 
						and  customer_cm.id_manager=".$user->id."
						and notification.read_".$user->role." = 0 
						and  user.status=1 
						order by id desc";
		} elseif($user->role == "responsable") {
			$query = "SELECT notification.* 
						from notification 
						inner join general_users as user on user.id = notification.id_user_to 
						inner join customer_cm on customer_cm.id_customer = user.id 
						inner join responsable_cm on responsable_cm.id_manager = customer_cm.id_manager 
						where notification.id_user_from !=".session('uid')." 
						and  responsable_cm.id_responsable=".$user->id."
						and notification.read_".$user->role." = 0 
						and  user.status=1 
						order by id desc";
						// var_dump($query);die();
		}
		// else {
		// 	$query = "Select notification.* from notification , ".USERS." user, ".$user->role."_group as groups, user_group as userg Where user.id = notification.id_user_to 
		//  and user.ids= userg.id_user and userg.id_group = groups.id_group 
		// 	and notification.read_".$user->role." = 0 and groups.id_user = '".$user->ids."' and notification.id_user_from !=".session('uid')." order by id desc";
		// }

		$query = $this->db->query($query);
		$notis = $query->result();
		foreach($notis as $noti){
			$this->db->update("notification", $data, array("id" => $noti->id));
		}
		ms(array(
			"status"  => "success",
			"message" => "",
		));
	}
	public function index() {
		$this->list();
	}

	public function list() {

		$user = $this->model->get("id,ids,role", USERS, "id = '".session('uid')."'");
		$query= "";
		if($user->role == "customer"){
			$query = "Select notification.* from notification , general_users user Where user.id = notification.id_user_to and user.id = '".session('uid')."' order by id desc";
			
		} elseif($user->role == "admin") {
			$query = "Select notification.* from notification where notification.id_user_from !=".session('uid')." order by id desc";
			// var_dump($query);die();
		} elseif($user->role == "manager") {
			$query = "SELECT notification.* 
						from notification 
						inner join general_users as user on user.id = notification.id_user_to 
						inner join customer_cm on customer_cm.id_customer = user.id 
						where notification.id_user_from !=".session('uid')." 
						and  customer_cm.id_manager=".$user->id."

						and  user.status=1 
						order by id desc";
		} elseif($user->role == "responsable") {
			$query = "SELECT notification.* 
						from notification 
						inner join general_users as user on user.id = notification.id_user_to 
						inner join customer_cm on customer_cm.id_customer = user.id 
						inner join responsable_cm on responsable_cm.id_manager = customer_cm.id_manager 
						where notification.id_user_from !=".session('uid')." 
						and  responsable_cm.id_responsable=".$user->id."
						and  user.status=1 
						order by id desc";
						// var_dump($query);die();
			// $query = "Select notification.* from notification , general_users user, ".$user->role."_group as groups, user_group as userg 
			// Where user.id = notification.id_user_to and user.ids= userg.id_user and userg.id_group = groups.id_group "
			// // and notification.read_".$user->role." = 0 
			// ."and groups.id_user = '".$user->ids."' and notification.id_user_from !=".session('uid')." and  user.status=1 order by id desc";
		}
		$query = $this->db->query($query);
		
		$data['notif'] = $query->result();
		// dump($data['notif']);
		$data['user'] = $user;
		if($data['notif']) {
			$this->template->build("list", $data);
		} else {
			$this->template->build("empty", $data);
		}
	}
	public function view($id){
		$user = $this->model->get("ids,role", USERS, "id = '".session('uid')."'");
		$data['read_'.$user->role] = 1;
		$this->db->update("notification", $data, array("id" => $id));
		$data['notif'] = $this->model->get("*","notification","id =".$id);
		if($data['notif']){
			$this->template->build("view", $data);
		}else{
			$this->template->build("list", $data);
		}
	}	
}