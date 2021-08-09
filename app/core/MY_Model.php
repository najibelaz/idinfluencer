<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MY_Model extends CI_Model
{
	private $userid;
	function __construct(){
		parent::__construct();
		// Load the Database Module REQUIRED for this to work.
		
		$this->userid = user_or_cm();
		$this->load->database();//Without it -> Message: Undefined property: XXXController::$db
	}
	
	function fetch($select = "*", $table = "", $where = "", $order = "", $by = "DESC", $start = -1, $limit = 0, $return_array = false)
	{
		$this->db->select($select);
		if($where != "")
		{
			$this->db->where($where);
		}
		if($order != "" && (strtolower($by) == "desc" || strtolower($by) == "asc"))
		{
			if($order == 'rand'){
				$this->db->order_by('rand()');
			}else{
				$this->db->order_by($order, $by);
			}
		}
		
		if((int)$start >= 0 && (int)$limit > 0)
		{
			$this->db->limit($limit, $start);
		}
		#Query
		$query = $this->db->get($table);
		if($return_array){
			$result = $query->result_array();
		} else {
			$result = $query->result();
		}
		$query->free_result();
		return $result;
	}	
	
	function get($select = "*", $table = "", $where = "", $order = "", $by = "DESC", $return_array = false)
	{
		$this->db->select($select);
		if($where != "")
		{
			$this->db->where($where);
		}
		if($order != "" && (strtolower($by) == "desc" || strtolower($by) == "asc"))
		{
			if($order == 'rand'){
				$this->db->order_by('rand()');
			}else{
				$this->db->order_by($order, $by);
			}
		}		
		#Query
		$query = $this->db->get($table);
		if($return_array){
			$result = $query->row_array();
		} else {
			$result = $query->row();
		}
		$query->free_result();

		return $result;
	}

	function getList($select = "*", $table = "", $where = "", $order = "", $by = "DESC", $return_array = false)
	{
		$this->db->select($select);
		if($where != "")
		{
			$this->db->where($where);
		}
		if($order != "" && (strtolower($by) == "desc" || strtolower($by) == "asc"))
		{
			if($order == 'rand'){
				$this->db->order_by('rand()');
			}else{
				$this->db->order_by($order, $by);
			}
		}		
		#Query
		$query = $this->db->get($table);
		$result = $query->result();
		$query->free_result();

		return $result;
	}
	function getCustomers_data($group_ids = null) {
		$this->db->select(USERS.'.*');
		$this->db->distinct();
		$this->db->from(USERS);
		$this->db->join('user_group', USERS.'.ids = user_group.id_user');
		//$this->db->join('manager_group', 'user_group.id_group = manager_group.id_group');
		
		$this->db->where("role = 'customer'");
		if($group_ids != null) {
			$this->db->where("id_group in (".$group_ids.")");
		}
		return $this->db->get()->result();
	}
	function getCustomers_data_sidebar($user=0) {
		if($user){
			$user = $this->model->get("id,ids,role", USERS, "id = '".$user."'");
		}else{
			$user = $this->model->get("id,ids,role", USERS, "id = '".session('uid')."'");
		}
		$this->db->distinct();
		$this->db->select(USERS.'.*');
		$this->db->from(USERS);
		if($user->role=="manager"){
			$this->db->join('customer_cm','customer_cm.id_customer = '.USERS.'.id' );
			$this->db->where('customer_cm.id_manager',$user->id);
		}elseif($user->role=="responsable"){
			$this->db->join('customer_cm','customer_cm.id_customer = '.USERS.'.id' );
			$this->db->join('responsable_cm','responsable_cm.id_manager = customer_cm.id_manager' );
			$this->db->where('responsable_cm.id_responsable',$user->id);
		}		// if($user->role == "manager" || $user->role == "responsable"){
		// 	$this->db->join('user_group', USERS.'.ids = user_group.id_user');
		// 	$this->db->join('manager_group', 'user_group.id_group = manager_group.id_group');
		// 	if($user->role == "responsable"){
		// 		$this->db->join('responsable_group', 'manager_group.id_group = responsable_group.id_group');
		// 		$this->db->where("responsable_group.id_user ='".$user->ids."'");
		// 	}else{
		// 		$this->db->where("manager_group.id_user ='".$user->ids."'");
		// 	}
		// }

		$this->db->where("role = 'customer'");
		$this->db->where("status = '1'");
		return $this->db->get()->result();
	}
	function getUsersByRole_data($from='') {
		$user = $this->model->get("id,id as IDUser,ids,role", USERS, "id = '".session('uid')."'");
		$this->db->distinct();
		$this->db->select(USERS.'.*,'.USERS.'.id as user_id,'.USERS.'.id as IDUser');
		$this->db->from(USERS);
		// if($user->role == "manager" || $user->role == "responsable"){
		// 	$this->db->join('user_group', USERS.'.ids = user_group.id_user');
		// 	$this->db->join('manager_group', 'user_group.id_group = manager_group.id_group');
		// 	if($user->role == "responsable"){
		// 		$this->db->join('responsable_group', 'manager_group.id_group = responsable_group.id_group');
		// 		$this->db->where("responsable_group.id_user ='".$user->ids."'");
		// 		$this->db->or_where("role = 'manager'");
		// 	}else{
		// 		$this->db->where("manager_group.id_user ='".$user->ids."'");
		// 	}
		// }
		// if (!$user->role == "admin") {
		// 	$this->db->or_where("role = 'customer'");
		// }
		// var_dump($user);die();

		if($user->role=="manager"){
			$this->db->join('customer_cm','customer_cm.id_customer = '.USERS.'.id' );
			$this->db->where('customer_cm.id_manager',$user->id);

		}elseif($user->role=="responsable"){
			$this->db->join('customer_cm','customer_cm.id_customer = '.USERS.'.id' );
			$this->db->join('responsable_cm','responsable_cm.id_manager = customer_cm.id_manager' );
			$this->db->where('responsable_cm.id_responsable',$user->id);
		}

		if(!empty($from) && $from == 'acceuil') {
			$this->db->limit(10, 0);
		}
		// var_dump($this->db->get()->result());
		return $this->db->get()->result();
	}
	function getUsersByRole_last3_month($from='') {
		$user = $this->model->get("id,ids,role", USERS, "id = '".session('uid')."'");
		$this->db->distinct();
		$this->db->select(USERS.'.*,'.USERS.'.id as user_id');
		$this->db->from(USERS);
		// if($user->role == "manager" || $user->role == "responsable"){
		// 	$this->db->join('user_group', USERS.'.ids = user_group.id_user');
		// 	$this->db->join('manager_group', 'user_group.id_group = manager_group.id_group');
		// 	if($user->role == "responsable"){
		// 		$this->db->join('responsable_group', 'manager_group.id_group = responsable_group.id_group');
		// 		$this->db->where("responsable_group.id_user ='".$user->ids."'");
		// 		$this->db->or_where("role = 'manager'");
		// 	}else{
		// 		$this->db->where("manager_group.id_user ='".$user->ids."'");
		// 	}
		// }
		// if (!$user->role == "admin") {
		// 	$this->db->or_where("role = 'customer'");
		// }
		// var_dump($user);die();

		if($user->role=="manager"){
			$this->db->join('customer_cm','customer_cm.id_customer = '.USERS.'.id' );
			$this->db->where('customer_cm.id_manager',$user->id);			
			$this->db->where(USERS.".expiration_date<=now()+interval 3 month and  expiration_date>=now()");
		}elseif($user->role=="responsable"){
			$this->db->join('customer_cm','customer_cm.id_customer = '.USERS.'.id' );
			$this->db->join('responsable_cm','responsable_cm.id_manager = customer_cm.id_manager' );
			$this->db->where('responsable_cm.id_responsable',$user->id);
		}
		$this->db->where(USERS.'.status=1');

		if(!empty($from) && $from == 'acceuil') {
			$this->db->limit(10, 0);
		}
		return $this->db->get()->result();
	}
	function get_account_data($groupid){
		$user = $this->model->get("ids,role", USERS, "id = '".session('uid')."'");
		$directory = APPPATH.'../public/';
		$scanned_directory = array_diff(scandir($directory), array('..', '.'));
		$data = array();
		foreach ($scanned_directory as $key => $directory) {
			$this->db->select('accounts.* , "'.$directory.'" as social_label');
			$this->db->from($directory.'_accounts accounts');
			if($user->role == "customer"){
				$this->db->where('accounts.uid',session('uid'));
				
			} elseif($user->role == "admin") {
				$this->db->join(USERS.' user','user.id= accounts.uid' );
				$this->db->join('user_group','user_group.id_user = user.ids' );
				$this->db->where('user_group.id_group',$groupid);
			}else{
				if(!session("cm_uid")){
					$this->db->join(USERS.' user','user.id= accounts.uid' );
					$this->db->join('user_group','user_group.id_user = user.ids' );
					$this->db->join($user->role.'_group as groups','groups.id_group = user_group.id_group' );
					$this->db->where('groups.id_user',$user->ids);
					$this->db->where('user_group.id_group',$groupid);
				}else{
					$this->db->where('accounts.uid',session('cm_uid'));
				}
			}
			

			$query = $this->db->get();
			$result =  $query->result();
			// dump($result);die;
			if($result) {
				foreach ($result as $item) {
					$data[] = $item;
				}
			}
		}
		return $data;
	}
	function getManager($group_ids = null) {
		$this->db->select(USERS.'.*');
		$this->db->distinct('ids');
		$this->db->from(USERS);
		$this->db->join('manager_group', USERS.'.ids = manager_group.id_user');
		//$this->db->join('manager_group', 'user_group.id_group = manager_group.id_group');
		$this->db->where("role = 'manager'");
		if($group_ids != null) {
			$this->db->where("id_group in (".$group_ids.")");
		}
		return $this->db->get()->result();
	}

	function getGroups($group_ids = null) {
		$this->db->select('*');
		$this->db->from('general_groups');
		$this->db->where("ids in (".$group_ids.")");
		return $this->db->get()->result();
	}

	function schedule_report($table, $status=2){
		$value_string = "";
		$date_string = "";

		$date_list = array();
		$date = strtotime(date('Y-m-d', strtotime(NOW)));
		for ($i=29; $i >= 0; $i--) { 
			$left_date = $date - 86400 * $i;
			$date_list[date('Y-m-d', $left_date)] = 0;
		}

		//Get data
		$query = $this->db->query("SELECT COUNT(status) as count, DATE(time_post) as time_post FROM ".$table." WHERE status = '{$status}' AND uid = '".$this->userid."' AND time_post > NOW() - INTERVAL 30 DAY GROUP BY DATE(".$table.".time_post);");
		if($query->result()){
			

			foreach ($query->result() as $key => $value) {
				if(isset($date_list[$value->time_post])){
					$date_list[$value->time_post] = $value->count;
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

	function schedules_report($status=2){
		$value_string = "";
		$date_string = "";
		$date_from=post('date_from');
		$date_to=post('date_to');
		if(post('date_from')==""){
			$date_from="01/".date("m/Y");	
		}
		if(post('date_to')==""){
			$date_to=date("d/m/Y");	
		}

		$date_list_fb = array();
		$date_list_tw = array();
		$date_list_ig = array();
		$where_date_from = "";
		$where_date_to = "";
		$diff_dates = 65;
		$date = strtotime(date('Y-m-d', strtotime(NOW."+1 month")));
		 
		if(isset($date_from) && !empty($date_from)){
			$date_from = strtotime(str_replace('/', '-', $date_from ));
			$newDate = date("Y-m-d", $date_from);
			$where_date_from = "AND time_post >= '".$newDate."' ";
			$diff_dates = 29;
			$date = strtotime(date('Y-m-d', $date_from). " + ".$diff_dates." days");

		}
		if(isset($date_to) && !empty($date_to)){
			$date_to = strtotime(str_replace('/', '-', $date_to ));
			$newDate = date("Y-m-d", $date_to);
			$where_date_to = "AND time_post <= '".$newDate."' ";
			$diff_dates = 29;
			$date = $date_to;
		}
		if(!empty($date_to)  && !empty($date_from)){
			$diff_dates = ($date_to - $date_from)/60/60/24;
			$date = strtotime(date("Y-m-d",$date_to));
		}
		for ($i=$diff_dates; $i >= 0; $i--) { 
			$left_date = $date - 86400 * $i;
			$date_list_fb[date('Y-m-d', $left_date)] = 0;
			$date_list_ig[date('Y-m-d', $left_date)] = 0;
			$date_list_tw[date('Y-m-d', $left_date)] = 0;
		}
		if(is_admin()){
			//Get data
			$query = $this->db->query("SELECT COUNT(status) as count, DATE(time_post) as time_post FROM facebook_posts WHERE  status=".ST_PUBLISHED." $where_date_to $where_date_from GROUP BY DATE(facebook_posts.time_post);");
			$query_insta = $this->db->query("SELECT COUNT(status) as count, DATE(time_post) as time_post FROM instagram_posts WHERE status=".ST_PUBLISHED."  $where_date_to $where_date_from GROUP BY DATE(instagram_posts.time_post);")->result();
			$query_tweet = $this->db->query("SELECT COUNT(status) as count, DATE(time_post) as time_post FROM twitter_posts WHERE status=".ST_PUBLISHED."  $where_date_to $where_date_from GROUP BY DATE(twitter_posts.time_post);")->result();
		}else{
			//Get data
			$query = $this->db->query("SELECT COUNT(status) as count, DATE(time_post) as time_post FROM facebook_posts WHERE status=".ST_PUBLISHED." AND uid = '".$this->userid."' $where_date_to $where_date_from GROUP BY DATE(facebook_posts.time_post);");
			$query_insta = $this->db->query("SELECT COUNT(status) as count, DATE(time_post) as time_post FROM instagram_posts WHERE status=".ST_PUBLISHED." AND uid = '".$this->userid."' $where_date_to $where_date_from GROUP BY DATE(instagram_posts.time_post);")->result();
			$query_tweet = $this->db->query("SELECT COUNT(status) as count, DATE(time_post) as time_post FROM twitter_posts WHERE status=".ST_PUBLISHED." AND uid = '".$this->userid."' $where_date_to $where_date_from GROUP BY DATE(twitter_posts.time_post);")->result();
		}

		if($query->result()){
			
			foreach ($query->result() as $key => $value) {
				if(isset($date_list_fb[$value->time_post])){
					$date_list_fb[$value->time_post] = $value->count;
				}
			}
			
		}
		if($query_tweet){
			
			foreach ($query_tweet as $key => $value) {
				if(isset($date_list_tw[$value->time_post])){
					$date_list_tw[$value->time_post] = $value->count;
				}
			}
			
		}
		if($query_insta){
			
			foreach ($query_insta as $key => $value) {
				if(isset($date_list_ig[$value->time_post])){
					$date_list_ig[$value->time_post] = $value->count;
				}
			}
			
		}

		foreach ($date_list_fb as $date => $value) {
			$value_string .= '{"period" : "'.$date.'","'.lang("facebook").'" : "'.$value.'","'.lang("instagram").'" : "'.$date_list_ig[$date].'","'.lang("twitter").'" : "'.$date_list_tw[$date].'"},';
			// $date_string .= "'{$date}',";
		}

		$value_string = "[".substr($value_string, 0, -1)."]";
		// $date_string  = "{".substr($date_string, 0, -1)."}";

		return (object)array(
			"value" => $value_string,
			// "date" => $date_string
		);
		
	}

	function update_status($table, $ids, $check_user){
		if(!empty(post())){
			if($check_user){
				$where = array("uid" => $this->userid);
			}else{
				$where = array();
			}

			$item = $this->model->get("id, status", $table, "ids = '{$ids}'");
			if(!empty($item)){
				$where["id"] = $item->id;
				$status = $item->status == 0?1:0;
				$tag = $item->status == 0?"tag-success":"tag-danger";
				$text = $item->status == 0?lang("enable"):lang("disable");
				$this->db->update($table, array('status' => $status), $where);
				ms(array(
					"status"  => "success",
					"tag"     => $tag,
					"text"    => $text,
					"message" => lang("update_status_successfully")
				));
			}else{
				ms(array(
					"status"  => "error",
					"message" => lang("cannot_update_status_please_try_again")
				));
			}
		}else{
			load_404();
		}
		
	}

	function delete($table, $ids, $check_user){
		if(!empty(post())){
			if($check_user){
				$where = array("uid" => $this->userid);
			}else{
				$where = array();
			}

			if(!$ids){
				ms(array(
					"status"  => "error",
					"message" => lang("select_an_item_to_delete")
				));
			}

			if(is_array($ids)){
				foreach ($ids as $key => $id) {
					$where["ids"] = $id;
					$this->db->delete($table, $where);
				}
				
				ms(array(
					"status"  => "success",
					"message" => lang("delete_successfully")
				));
			}else{
				$item = $this->model->get("id", $table, "ids = '{$ids}'");
				if(!empty($item)){
					$where["id"] = $item->id;
					$this->db->delete($table, $where);
					ms(array(
						"status"  => "success",
						"message" => lang("delete_successfully")
					));
				}else{
					ms(array(
						"status"  => "error",
						"message" => lang("delete_failed_please_try_again")
					));
				}
			}
		}else{
			load_404();
		}
	}
	
	function history_ip($userid){
		$user = $this->model->get("id, history_ip", USERS, "id = '{$userid}'");	
		if(!empty($user)){
			$history_ip_old = (array)json_decode($user->history_ip);
			$history_ip = ($history_ip_old == "")?array():$history_ip_old;
			/*$finder = get_curl("http://ip-api.com/json");
			$finder = json_decode($finder);*/
			$history_ip[] = get_client_ip();

			if(count($history_ip) >= 10){
				array_shift($history_ip);
			}

			$this->db->update(USERS, array('history_ip' => json_encode($history_ip)), array("id" => $userid));
		}	
	}

	function get_proxies($table, $id = 0, $field = "id", $limit = true, $data){

		$uid = $this->userid;
		if(!empty($data) && isset($data->uid)){
			$uid = $data->uid;
		}

		if(get_option('system_proxy', 1) == 1){
			$this->db->select("proxies.*, COUNT(accounts.default_proxy) as total");
			$this->db->from(PROXIES." as proxies");
			$this->db->join($table." as accounts", "proxies.id = accounts.default_proxy", "left");
			$this->db->where("proxies.status = 1");
			if($id != 0){
				$this->db->where("proxies.id = {$id}");
			}

			$this->db->group_by("proxies.id");
			$this->db->order_by("total", "ASC");
			if(!file_exists(APPPATH."../plugins/proxy_manager/")){
				$this->db->limit(1, 0);
			}
			$query = $this->db->get();

			if($query->result()){
				$result = $query->result();
				if(file_exists(APPPATH."../plugins/proxy_manager/")){

					$user = $this->model->get("*", USERS, "id = '".$uid."'");
					foreach ($result as $key => $row) {
						if($row->total >= (int)$row->limit){
							unset($result[$key]);
						}

						$packages = json_decode($row->package);
						if(!empty($packages)){
							if(!empty($packages) && !empty($user) &&  !in_array($user->package, $packages) && (int)$user->admin == 0){
								unset($result[$key]);
							}
						}
					}

					$result = array_values($result);
				}

				if(!empty($result)){
					if($limit && !empty($result)){
						return $result[0]->$field;
					}else{
						return $result;
					}
				}
			}
		}

		return false;
	}

	function get_storage($check_type = "", $size = 0){
		$user = $this->model->get("*", USERS, "id = '".$this->userid."'");
		$data = array(
			"max_storage_size" => 100,
			"max_file_size" => 5,
			"total_storage_size" => 0
		);

		$this->db->select("uid, SUM(file_size) AS size");
		$this->db->from(FILE_MANAGER);
		$this->db->where("uid", $this->userid);
		$this->db->group_by("uid");
		$query = $this->db->get();
		if($query->row()){
			$result = $query->row();
			if(!empty($result)){
				$total_size = (float)$result->size/1024;
				$data['total_storage_size'] = $total_size;
			}
		} else{
			
		}

		
		if(!empty($user)){
			$pack = $this->model->get("*", PACKAGES, "id = '".$user->package."'");
			$permission = (array)json_decode($user->permission);
			$pack_permission = (array)json_decode($pack->permission);
			if(!empty($permission)){
				if(isset($pack_permission['max_storage_size'])){
					$data['max_storage_size'] = $pack_permission['max_storage_size'];
				}

				if(isset($pack_permission['max_file_size'])){
					$data['max_file_size'] = $pack_permission['max_file_size'];
				}
			}
		}

		$data = (object)$data;

		switch ($check_type) {
			case 'storage':
				$total_size = $data->total_storage_size + $size/1024;
				if($total_size > $data->max_storage_size){
					ms(array(
						"status" => "error",
						"message" => lang("you_have_exceeded_the_storage_limit")
					));
				}
				break;

			case 'file':
				$size = $size/1024;
				if($size > $data->max_file_size){
					ms(array(
						"status" => "error",
						"message" => lang("you_have_exceeded_the_file_limit")
					));
				}

				$total_size = $data->total_storage_size + $size;
				if($total_size > $data->max_storage_size){
					ms(array(
						"status" => "error",
						"message" => lang("you_have_exceeded_the_storage_limit")
					));
				}
				break;
			
			default:
				return $data;
				break;
		}
	}		
	function sendemailaddNewUser(){
		$mail = new PHPMailer(true);
		$mail->CharSet = "utf-8";
		try {
			if(get_option("email_protocol_type", "mail") == "smtp" && get_option("email_smtp_server", "") != "" && get_option("email_smtp_port", "") != ""){
				$mail->isSMTP();
				$mail->SMTPAuth = true;
				$mail->Host = get_option("email_smtp_server", "");
				$mail->Username = get_option("email_smtp_username", "");
				$mail->Password = get_option("email_smtp_password", "");
				$mail->SMTPSecure = get_option("email_smtp_encryption", "");
				$mail->Port = get_option("email_smtp_port", "");
			}else{
				$mail->isMail();
			}
			$email_from = get_option('email_from', '')?get_option('email_from', ''):"do-not-reply@gmail.com";
			$email_name = get_option('email_name', '')?get_option('email_name', ''):get_option('website_title', 'Social Planer - Social Marketing Tool');
			//Recipients
			$mail->setFrom($email_from, $email_name);
			$mail->addAddress("a.masraman@egiodigital.com", "Anass Masraman");
			//Content
			$mail->isHTML(true);
			$mail->Subject = "Hello Anass";
			$mail->Body    = "Hello Anass";
			$mail->AltBody = "Hello Anass";
			$mail->send();
			return false;
		} catch (Exception $e) {
			return 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo;
		}
	}
	function send_email_crone($content){
		$mail = new PHPMailer(true);
		$mail->CharSet = "utf-8";
		try {
			if(get_option("email_protocol_type", "mail") == "smtp" && get_option("email_smtp_server", "") != "" && get_option("email_smtp_port", "") != ""){
				$mail->isSMTP();
				$mail->SMTPAuth = true;
				$mail->Host = get_option("email_smtp_server", "");
				$mail->Username = get_option("email_smtp_username", "");
				$mail->Password = get_option("email_smtp_password", "");
				$mail->SMTPSecure = get_option("email_smtp_encryption", "");
				$mail->Port = get_option("email_smtp_port", "");
			}else{
				$mail->isMail();
			}
			$email_from = get_option('email_from', '')?get_option('email_from', ''):"do-not-reply@gmail.com";
			$email_name = get_option('email_name', '')?get_option('email_name', ''):get_option('website_title', 'Social Planer - Social Marketing Tool');
			//Recipients
			$mail->setFrom($email_from, $email_name);
			$mail->addAddress("a.masraman@egiodigital.com", "Anass Masraman");
			//Content
			$mail->isHTML(true);
			$mail->Subject = "Log Crone ".date("Y-m-d");
			$mail->Body    = $content;
			$mail->AltBody = "Log Crone ".date("Y-m-d");
			$mail->send();
			return false;
		} catch (Exception $e) {
			return 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo;
		}
	}
	function send_email_croneFb($content){
		$mail = new PHPMailer(true);
		$mail->CharSet = "utf-8";
		try {
			if(get_option("email_protocol_type", "mail") == "smtp" && get_option("email_smtp_server", "") != "" && get_option("email_smtp_port", "") != ""){
				$mail->isSMTP();
				$mail->SMTPAuth = true;
				$mail->Host = get_option("email_smtp_server", "");
				$mail->Username = get_option("email_smtp_username", "");
				$mail->Password = get_option("email_smtp_password", "");
				$mail->SMTPSecure = get_option("email_smtp_encryption", "");
				$mail->Port = get_option("email_smtp_port", "");
			}else{
				$mail->isMail();
			}
			$email_from = get_option('email_from', '')?get_option('email_from', ''):"do-not-reply@gmail.com";
			$email_name = get_option('email_name', '')?get_option('email_name', ''):get_option('website_title', 'Social Planer - Social Marketing Tool');
			//Recipients
			$mail->setFrom($email_from, $email_name);
			$mail->addAddress("a.masraman@egiodigital.com", "Anass Masraman");
			//Content
			$mail->isHTML(true);
			$mail->Subject = "Log Cron date - Publication de la page Facebook";
			$mail->Body    = $content;
			$mail->AltBody = "Log Cron date - Publication de la page Facebook";
			$mail->send();
			return false;
		} catch (Exception $e) {
			return 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo;
		}
	}
	function send_email_activation($subject, $email,$password, $userid){
		$user = $this->db->select("*")->from(USERS)->where("id", $userid)->get()->row();
		$package = $this->db->select("*")->from(PACKAGES)->where("type", 1)->get()->row();
		if(!empty($user)){
			$mail = new PHPMailer(true);
			$mail->CharSet = "utf-8";
			try {

			    if(get_option("email_protocol_type", "mail") == "smtp" && get_option("email_smtp_server", "") != "" && get_option("email_smtp_port", "") != ""){
					$mail->isSMTP();
					$mail->SMTPAuth = true;
					$mail->Host = get_option("email_smtp_server", "");
					$mail->Username = get_option("email_smtp_username", "");
					$mail->Password = get_option("email_smtp_password", "");
					$mail->SMTPSecure = get_option("email_smtp_encryption", "");
					$mail->Port = get_option("email_smtp_port", "");

				}else{
					$mail->isMail();
				}

				$email_from = get_option('email_from', '')?get_option('email_from', ''):"do-not-reply@gmail.com";
				$email_name = get_option('email_name', '')?get_option('email_name', ''):get_option('website_title', 'Social Planer - Social Marketing Tool');

			    //Recipients
			    $mail->setFrom($email_from, $email_name);
			    $mail->addAddress($user->email, $user->fullname);

				$template = Modules::run("email/template_activation");
				
				$template = str_replace("{email}", $email, $template);
				$template = str_replace("{password}", $password, $template);
			    //Content
			    $mail->isHTML(true);
			    $mail->Subject = $subject;
			    $mail->Body    = $template;
			    $mail->AltBody = $subject;

			    $mail->send();
			    return false;
			} catch (Exception $e) {
				return 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo;
			}

		}
	}
	function send_email($subject, $content, $userid){
		$user = $this->db->select("*")->from(USERS)->where("id", $userid)->get()->row();
		$package = $this->db->select("*")->from(PACKAGES)->where("type", 1)->get()->row();
		if(!empty($user)){
			//Send email
			$subject = nl2br($subject);
			$content = nl2br($content);

			$now = (int)strtotime(date("Y-m-d", strtotime(NOW)));
			$expiration_date = (int)strtotime($user->expiration_date);
			$days_left = ($expiration_date - $now)/(60*60*24);

			//Replace Subject
			$subject = str_replace("{full_name}", $user->fullname, $subject);
			$subject = str_replace("{days_left}", $days_left, $subject);
			$subject = str_replace("{expiration_date}", convert_date($user->expiration_date), $subject);
			$subject = str_replace("{trial_days}", $package->trial_day, $subject);
			$subject = str_replace("{email}", $user->email, $subject);
			$subject = str_replace("{activation_link}", cn("auth/activation/".$user->activation_key), $subject);
			$subject = str_replace("{recovery_password_link}", cn("auth/activation/".$user->reset_key), $subject);
			$subject = str_replace("{website_link}", cn(""), $subject);
			$subject = str_replace("{website_name}", get_option("website_title", "Stackposts - Social Marketing Tool"), $subject);

			//
			$content = str_replace("{full_name}", $user->fullname, $content);
			$content = str_replace("{days_left}", $days_left, $content);
			$content = str_replace("{expiration_date}", convert_date($user->expiration_date), $content);
			$content = str_replace("{trial_days}", $package->trial_day, $content);
			$content = str_replace("{email}", $user->email, $content);
			$content = str_replace("{activation_link}", cn("auth/activation/".$user->activation_key), $content);
			$content = str_replace("{recovery_password_link}", cn("auth/reset_password/".$user->reset_key), $content);
			$content = str_replace("{website_link}", cn(""), $content);
			$content = str_replace("{website_name}", get_option("website_title", "Stackposts - Social Marketing Tool"), $content);


			$template = Modules::run("email/template");
			$template = str_replace("{content}", $content, $template);

			$mail = new PHPMailer(true);
			$mail->CharSet = "utf-8";
			try {

			    if(get_option("email_protocol_type", "mail") == "smtp" && get_option("email_smtp_server", "") != "" && get_option("email_smtp_port", "") != ""){
					$mail->isSMTP();
					$mail->SMTPAuth = true;
					$mail->Host = get_option("email_smtp_server", "");
					$mail->Username = get_option("email_smtp_username", "");
					$mail->Password = get_option("email_smtp_password", "");
					$mail->SMTPSecure = get_option("email_smtp_encryption", "");
					$mail->Port = get_option("email_smtp_port", "");

				}else{
					$mail->isMail();
				}

				$email_from = get_option('email_from', '')?get_option('email_from', ''):"do-not-reply@gmail.com";
				$email_name = get_option('email_name', '')?get_option('email_name', ''):get_option('website_title', 'Social Planer - Social Marketing Tool');

			    //Recipients
			    $mail->setFrom($email_from, $email_name);
			    $mail->addAddress($user->email, $user->fullname);

			    //Content
			    $mail->isHTML(true);
			    $mail->Subject = $subject;
			    $mail->Body    = $template;
			    $mail->AltBody = $subject;

			    $mail->send();
			    return false;
			} catch (Exception $e) {
				return 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo;
			}

		}
	}
	function send_email_game($subject, $content, $userid){
		$user = $this->db->select("*")->from('participant_jeux')->where("id", $userid)->get()->row();
		$game = $this->db->select("*")->from('jeux_concours')->where("ids", $user->id_game)->get()->row();
		$usergame = $this->db->select("*")->from(USERS)->where("id", $game->id_user)->get()->row();
		$userinfo = $this->db->select("*")->from('user_information')->where("id_user", $usergame->id)->get()->row();
		$subjectMail = $game->objet_mail;
		$contentMail = $game->email_participant;
		if(!empty($subjectMail)) {
			$subject = $subjectMail;
		}
		if(!empty($contentMail)) {
			$content = $contentMail;
		}
		if(!empty($user)){
			//Send email
			$subject = nl2br($subject);
			$content = nl2br($content);

			$now = (int)strtotime(date("Y-m-d", strtotime(NOW)));
			$expiration_date = (int)strtotime($user->expiration_date);
			$days_left = ($expiration_date - $now)/(60*60*24);

			//Replace Subject
			$subject = str_replace("{full_name}", $user->nom.$user->prenom, $subject);
			$subject = str_replace("{email}", $user->email, $subject);
			$subject = str_replace("{game_name}",$game->name , $subject);
			$subject = str_replace("{rs}", $usergame->rs , $subject);

			//
			$content = str_replace("{Name}", $user->prenom, $content);
			$content = str_replace("{name}", $user->prenom, $content);
			$content = str_replace("{full_name}", $user->nom.' '.$user->prenom, $content);
			$content = str_replace("{email}", $user->email, $content);
			$content = str_replace("{game_name}",$game->name , $content);
			$content = str_replace("{rs}",$usergame->rs , $content);
			$template = Modules::run("email/template_game");
			$template = str_replace("{content}", $content, $template);
			$template = str_replace("{logo}", $usergame->avatar, $template);
			$template = str_replace("{info}", $usergame->rs, $template);
			$template = str_replace("{website_game}", $userinfo->website, $template);

			$mail = new PHPMailer(true);
			$mail->CharSet = "utf-8";
			try {

			    if(get_option("email_protocol_type", "mail") == "smtp" && get_option("email_smtp_server", "") != "" && get_option("email_smtp_port", "") != ""){
					$mail->isSMTP();
					$mail->SMTPAuth = true;
					$mail->Host = get_option("email_smtp_server", "");
					$mail->Username = get_option("email_smtp_username", "");
					$mail->Password = get_option("email_smtp_password", "");
					$mail->SMTPSecure = get_option("email_smtp_encryption", "");
					$mail->Port = get_option("email_smtp_port", "");

				}else{
					$mail->isMail();
				}

				$email_from = $usergame->email;
				$email_name = $usergame->rs;

			    //Recipients
			    $mail->setFrom($email_from, $email_name);
			    $mail->addAddress($user->email, $user->fullname);

			    //Content
			    $mail->isHTML(true);
			    $mail->Subject = $subject;
			    $mail->Body    = $template;
			    $mail->AltBody = $subject;

			    $mail->send();
			    return false;
			} catch (Exception $e) {
				return 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo;
			}

		}
	}
	function send_email_parrain($subject, $content, $userid,$to){
		$user = $this->db->select("*")->from('participant_jeux')->where("id", $userid)->get()->row();
		$game = $this->db->select("*")->from('jeux_concours')->where("ids", $user->id_game)->get()->row();
		$usergame = $this->db->select("*")->from(USERS)->where("id", $game->id_user)->get()->row();
		$userinfo = $this->db->select("*")->from('user_information')->where("id_user", $usergame->id)->get()->row();
		$subjectMail = $game->object_parrain;
		$contentMail = $game->email_parrain;
		
		if(!empty($subjectMail)) {
			$subject = $subjectMail;
		}
		if(!empty($subjectMail)) {
			$content = $contentMail;
		}
		if(!empty($user)){
			//Send email
			$subject = nl2br($subject);
			$content = nl2br($content);
			$link = '<a href="'.cn("game/".$game->slug."?parrain=".$user->ids).'">'.$game->name.'</a>';
			
			//Replace Subject
			$subject = str_replace("{full_name}", $user->nom.' '.$user->prenom, $subject);
			$subject = str_replace("{name}", $user->prenom, $subject);
			$subject = str_replace("{game_name}", $game->name , $subject);
			$subject = str_replace("{rs}", $usergame->rs , $subject);
			
			//
			$content = str_replace("{full_name}", $user->nom.' '.$user->prenom, $content);
			$content = str_replace("{name}", $user->prenom, $content);
			$content = str_replace("{game_name}", $link, $content);
			$content = str_replace("{link_game}", $link, $content);
			$content = str_replace("{rs}", $usergame->rs, $content);

			$template = Modules::run("email/template_game");
			$template = str_replace("{content}", $content, $template);
			$template = str_replace("{logo}", $usergame->avatar, $template);
			$template = str_replace("{info}", $usergame->rs, $template);
			$template = str_replace("{website_game}", $userinfo->website, $template);
			
			$mail = new PHPMailer(true);
			$mail->CharSet = "utf-8";
			try {
				
				if(get_option("email_protocol_type", "mail") == "smtp" && get_option("email_smtp_server", "") != "" && get_option("email_smtp_port", "") != ""){
					$mail->isSMTP();
					$mail->SMTPAuth = true;
					$mail->Host = get_option("email_smtp_server", "");
					$mail->Username = get_option("email_smtp_username", "");
					$mail->Password = get_option("email_smtp_password", "");
					$mail->SMTPSecure = get_option("email_smtp_encryption", "");
					$mail->Port = get_option("email_smtp_port", "");
					
				}else{
					$mail->isMail();
				}

				$email_from = $usergame->email;
				$email_name = $usergame->rs;

			    //Recipients
				$mail->setFrom($email_from, $email_name);
				foreach ($to as $key => $value) {
					$mail->addAddress($value, $value);
				}
				
			    //Content
			    $mail->isHTML(true);
			    $mail->Subject = $subject;
			    $mail->Body    = $template;
				$mail->AltBody = $subject;
			    $mail->send();
			    return false;
			} catch (Exception $e) {
				return 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo;
			}

		}
	}
	function count_posts($userid,$status){
		$query = "Select ";
		$directory = APPPATH.'../public/';
		$scanned_directory = array_diff(scandir($directory), array('..', '.'));
		foreach ($scanned_directory as $key => $directory) {
			$query .= "(select  count(uid) FROM ".$directory."_posts WHERE uid = ".$userid." and status = ".$status." and MONTH(time_post) = MONTH(CURRENT_DATE())) +";
		}
		$query = substr($query, 0, -1);
		$query .= " as total_of_posts";
		$query = $this->db->query($query);
		if($query) {
			$data = $query->row();
			return $data->total_of_posts;
		}
		return 0;
		// $query = "Select number_posts from ".PACKAGES." pack, ".USERS." user Where user.package = pack.id and user.id=".$userid;
		// $query = $this->db->query($query);
		// $data = $query->row();
		// return (INT)$data->number_posts;
		
	}
	function count_posts_user($userid,$status){
		// $query = "Select ";
		// $directory = APPPATH.'../public/';
		// $scanned_directory = array_diff(scandir($directory), array('..', '.'));
		// foreach ($scanned_directory as $key => $directory) {
		// 	$query .= "(select  count(uid) FROM ".$directory."_posts WHERE uid = ".$userid." and status = ".$status." and MONTH(time_post) = MONTH(CURRENT_DATE())) +";
		// }
		// $query = substr($query, 0, -1);
		// $query .= " as total_of_posts";
		// $query = $this->db->query($query);
		// if($query) {
		// 	$data = $query->row();
		// 	return $data->total_of_posts;
		// }
		// return 0;
		$query = "Select number_posts from ".PACKAGES." pack, ".USERS." user Where user.package = pack.id and user.id=".$userid;
		$query = $this->db->query($query);
		$data = $query->row();
		return (INT)$data->number_posts;
		
	}
	
	function count_all_posts($ids,$status){
		$query = "Select ";
		$directory = APPPATH.'../public/';
		$scanned_directory = array_diff(scandir($directory), array('..', '.'));
		foreach ($scanned_directory as $key => $directory) {
			$query .= "(select  count(uid) FROM ".$directory."_posts WHERE uid in ( ".$ids." ) and status = ".$status." and MONTH(time_post) = MONTH(CURRENT_DATE())) +";
		}
		$query = substr($query, 0, -1);
		$query .= " as total_of_posts";
		$query = $this->db->query($query);
		$data = $query->row();
		return $data->total_of_posts;
		
	}

	function count_posts_socials($userid,$status){
		$query = "Select ";
		$directory = APPPATH.'../public/';
		$scanned_directory = array_diff(scandir($directory), array('..', '.'));
		$where_user= "";
		$where_datefrom= "";
		$where_dateto= "";
		if(!$userid==""){
			$where_user= " and uid = ".$userid." ";
		}
		$date_from=post('date_from');
		$date_to=post('date_to');
		$this->db->select('count(*) as sum');

		if(isset($date_from) && !empty($date_from)){
			$date_from = strtotime(str_replace('/', '-', $date_from ));
			$newDate = date("Y-m-d", $date_from);
			$where_datefrom= " and time_post >= '".$newDate."' ";

		}
		if(isset($date_to) && !empty($date_to)){
			$date_to = strtotime(str_replace('/', '-', $date_to ));
			$newDate = date("Y-m-d", $date_to);
			$where_dateto = " and time_post <= '".$newDate."' ";
		}
		if(empty($date_to)  && empty($date_from)){
			$where_dateto =" and (MONTH(time_post) = MONTH(CURRENT_DATE()) OR MONTH(time_post) = MONTH(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH)))";
		}		
		foreach ($scanned_directory as $key => $directory) {
			$query .= "(select  count(uid) FROM ".$directory."_posts WHERE status = ".$status.$where_user.$where_datefrom.$where_dateto.") as ".$directory.",";
		}
		$query = substr($query, 0, -1);
		$query = $this->db->query($query);
		$data = $query->row();
		return $data;
		
	}
	function posts_solde($userid){
		$query = "Select number_posts from ".PACKAGES." pack, ".USERS." user Where user.package = pack.id and user.id=".$userid;
		$query = $this->db->query($query);
		$data = $query->row();
		return (INT)$data->number_posts;
		
	}
	function getCustomers_relation($ids) {
		$user = $this->model->get("ids,role", USERS, "id = '".session('uid')."'");
		$this->db->distinct();
		$this->db->select(USERS.'.*');
		$this->db->from(USERS);
		if($user->role == "manager" || $user->role == "responsable"){
			$this->db->join('user_group', USERS.'.ids = user_group.id_user');
			$this->db->join('manager_group', 'user_group.id_group = manager_group.id_group');
			if($user->role == "responsable"){
				$this->db->join('responsable_group', 'manager_group.id_group = responsable_group.id_group');
				$this->db->where_in("responsable_group.id_user",$ids);
			}else{
				$this->db->where_in("manager_group.id_user",$ids);
			}
		}
		$this->db->or_where("role != 'customer'");
		return $this->db->get()->result();
	}
	function notification_count(){
		$user = $this->model->get("id,ids,role", USERS, "id = '".session('uid')."'");
		$data = array();
		if($user->role == "customer"){
			$query = "Select count(notification.id_user_to) as count from notification , ".USERS." user Where user.id = notification.id_user_to and notification.read_customer = 0 and user.id = '".session('uid')."'";
			$query = $this->db->query($query);
			$data['count'] = $query->row()->count;
			$query = "Select notification.* from notification , ".USERS." user Where user.id = notification.id_user_to and user.id = '".session('uid')."'"." order by time_notif desc";
			$query = $this->db->query($query);
			$data['notif'] = $query->result();
			
		}elseif($user->role == "admin"){
			$query = "Select count(notification.id_user_to) as count from notification , ".USERS." user Where user.id = notification.id_user_to and notification.read_".$user->role." = 0 and notification.id_user_from !=".session('uid');
			$query = $this->db->query($query);
			$data['count'] = $query->row()->count;
			$query = "Select notification.* from notification , ".USERS." user Where user.id = notification.id_user_to and notification.id_user_from !=".session('uid')." order by time_notif desc";
			$query = $this->db->query($query);
			$data['notif'] = $query->result();
		} elseif($user->role == "manager") {
			$query = "SELECT count(notification.id_user_to) as count 
					from notification 
					inner join general_users as user on user.id = notification.id_user_to 
					inner join customer_cm on customer_cm.id_customer = user.id 
					where notification.id_user_from !=".session('uid')." 
					and  customer_cm.id_manager=".$user->id."
					and notification.read_".$user->role." = 0 
					and  user.status=1 ";
			
			$query = $this->db->query($query);
			$data['count'] = $query->row()->count;
			$query = "SELECT notification.*
				from notification 
				inner join general_users as user on user.id = notification.id_user_to 
				inner join customer_cm on customer_cm.id_customer = user.id 
				where notification.id_user_from !=".session('uid')." 
				and  customer_cm.id_manager=".$user->id."
				and notification.read_".$user->role." = 0 
				and  user.status=1 
				order by time_notif desc";
				
			$query = $this->db->query($query);
			$data['notif'] = $query->result();
		} elseif($user->role == "responsable") {
			$query = "SELECT count(notification.id_user_to) as count 
						from notification 
						inner join general_users as user on user.id = notification.id_user_to 
						inner join customer_cm on customer_cm.id_customer = user.id 
						inner join responsable_cm on responsable_cm.id_manager = customer_cm.id_manager 
						where notification.id_user_from !=".session('uid')." 
						and  responsable_cm.id_responsable=".$user->id."
						and notification.read_".$user->role." = 0 
						and  user.status=1 
						";
			// else{
			// $query = "Select count(notification.id_user_to) as count from notification , ".USERS." user, ".$user->role."_group as groups, user_group as userg 
			// Where user.id = notification.id_user_to 
			// and user.ids= userg.id_user 
			// and userg.id_group = groups.id_group 
			// and notification.read_".$user->role." = 0 
			// and groups.id_user = '".$user->ids."' 
			// and notification.id_user_from !=".session('uid');
			// echo $query;
			$query = $this->db->query($query);
			$data['count'] = $query->row()->count;
			$query = "SELECT notification.*
						from notification 
						inner join general_users as user on user.id = notification.id_user_to 
						inner join customer_cm on customer_cm.id_customer = user.id 
						inner join responsable_cm on responsable_cm.id_manager = customer_cm.id_manager 
						where notification.id_user_from !=".session('uid')." 
						and  responsable_cm.id_responsable=".$user->id."
						and notification.read_".$user->role." = 0 
						and  user.status=1 
						order by time_notif desc";

			// $query = "Select notification.* from notification , ".USERS." user, ".$user->role."_group as groups, user_group as userg 
			// Where user.id = notification.id_user_to 
			// and user.ids= userg.id_user 
			// and userg.id_group = groups.id_group 
			// and notification.read_".$user->role." = 0 
			// and groups.id_user = '".$user->ids."' 
			// and notification.id_user_from !=".session('uid')." 
			// order by time_notif desc";
			$query = $this->db->query($query);
			$data['notif'] = $query->result();
			// var_dump($data);die();
		}
		return $data;
		
	}
	function refresh_token(){
		
		$clientId = get_option('teamleader_id_client');
		$clientSecret = get_option('teamleader_secret_client');
		$refresh_token = get_option('teamleader_token_refresh');
		// if($refresh_token==null){
		// 	$code=get_option("teamleader_code");
		// 	$ch = curl_init();
		//     curl_setopt($ch, CURLOPT_URL, 'https://app.teamleader.eu/oauth2/access_token');
		//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// 	curl_setopt($ch, CURLOPT_POST, true);
		// 	$params=array(
		//         'code' => $code,
		//         'client_id' => $clientId,
		//         'client_secret' => $clientSecret,
		//         'redirect_uri' => 'https://espaceclient.idinfluencer.com',
		//         'grant_type' => 'authorization_code'
		// 	);
			
		// 	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
			
		// 	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded"));

		//     $response = curl_exec($ch);
		// 	$data = json_decode($response, true);
		// 	var_dump($data,$code);die();
		//     $accessToken = $data['access_token'];
		// }
		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL => "https://app.teamleader.eu/oauth2/access_token",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => array(
			'client_id' => $clientId,
			'client_secret' => $clientSecret,
			'refresh_token' => $refresh_token,
			'grant_type' => 'refresh_token'
		),
		));

		$response = curl_exec($curl);
		$data = json_decode($response, true);
		$accessToken = $data['access_token'];
		$accessTokenRefresh = $data['refresh_token'];
		update_option('teamleader_token', $accessToken);
		update_option('teamleader_token_refresh', $accessTokenRefresh);
		return $accessToken;
	}	
	function add_souscription($ids = ""){
		$company_id = $ids;
		// $acess = $this->model->refresh_token();
		$accessToken = get_option('teamleader_token');
			
		/**
		 * Get the user identity information using the access token.
		 */
		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => "https://api.teamleader.eu/deals.list",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_POSTFIELDS =>"{
					\"filter\": {
					\"customer\": {
						\"type\": \"company\",
						\"id\": \"$company_id\"
				}
			},
				\"page\": {
					\"size\": 20,
					\"number\": 1
				}
		}",
		CURLOPT_HTTPHEADER => array(
			"Authorization: Bearer ".$accessToken,
			"Content-Type: application/json"
		),
		));

		$response = curl_exec($curl);
		curl_close($curl);
		$response = json_decode($response, true);
		$invoices = $response['data'];
		$return["result"] = false;
		$return["date"] = "";
		foreach ($invoices as $key => $invoice) {
			$sousc = $this->model->get("*", "souscription", "No = '".$invoice['reference']."'");
			$data = array(
				"No" => $invoice['reference'],
				"Client" => $invoice['title'],
				"Montant" => $invoice['estimated_value']['amount'],
				"date" => $invoice['created_at'],
				"id_team" => $ids,
				"phase" => $invoice['current_phase']["id"],
				"status" => $invoice['status'],
			);
			$return["date"] = $invoice['created_at'];
			$curl = curl_init();
			$phase =$invoice['current_phase']["id"];
			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://api.teamleader.eu/dealPhases.list",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_POSTFIELDS =>"{
							\"filter\": {
							\"ids\": [
								\"$phase\"
							]
					}
				}",
				CURLOPT_HTTPHEADER => array(
					"Authorization: Bearer ".$accessToken,
					"Content-Type: application/json"
				),
			));

			$response = curl_exec($curl);
			curl_close($curl);
			$response = json_decode($response, true);
			if($invoice['status'] == "won"){
				$return["result"] = true; 
			}
				$data["phase"] = $response["data"][0]["name"];
				if(!$sousc){
					$this->db->insert('souscription', $data);
				}else{
					$this->db->update('souscription', $data, array("id" => $sousc->id));
				}
		}
		return $return;
		
	}
	
}
