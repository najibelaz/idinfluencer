<?php
defined('BASEPATH') OR exit('No direct script access allowed');
function date_compare($a, $b){
    $t1 = strtotime($a->created);
    $t2 = strtotime($b->created);
    return $t2 - $t1;
} 

class post_model extends MY_Model {
	private $userid;
	public function __construct(){
		parent::__construct();
		$this->userid = user_or_cm();
	}

	public function get_accounts(){
		
		if(is_manager() || is_admin() || is_responsable()){
		$this->userid =user_or_cm();
		}
		$directory = APPPATH.'../public/';
		$scanned_directory = array_diff(scandir($directory), array('..', '.'));
		
		$data = array();
		foreach ($scanned_directory as $key => $directory) {
			$tb_accounts = $directory."_accounts";
			$tb_posts = $directory."_posts";


			$can_post = true;
			if(strpos($directory, ".") === false){
				$model_name = $directory."_model";
				$this->load->model($directory.'/'.$model_name, $model_name);
				
				$methods = get_class_methods($this->$model_name);
				if(!in_array("post_handler", $methods)){
					$can_post = false;
				}
				
			}
			if ( $this->db->table_exists($tb_accounts) && $this->db->table_exists($tb_posts) && $can_post){
				
				if($directory == "facebook"){
					$accounts = $this->model->fetch("*", $tb_accounts, "status = 1 AND uid = '".$this->userid."' AND official != 3");
				}else{
					$accounts = $this->model->fetch("*", $tb_accounts, "status = 1 AND uid = '".$this->userid."'");
				}
				if(!empty($accounts)){
					foreach ($accounts as $key => $account) {
						$data[] = (object)array(
							"category" => $directory,
							"username" => isset($account->username)?$account->username:$account->fullname,
							"type" => isset($account->type)?$account->type:"profile",
							"pid" => $account->ids,
							"avatar" => $account->avatar,
							"created" => $account->created,
						);
					}
				}
			}

		}
		
		usort($data, 'date_compare');
		
		return $data;
	}
	function getpost_info($id,$social){
		
		return $this->db->select('data,time_post,'.$social.'_accounts.ids as pid, '.$social.'_posts.type as type')
			->where($social."_posts.id",$id)
			->order_by($social.'_posts.id',"desc")
			->limit(1)
			->join($social.'_accounts', $social.'_posts.account='.$social.'_accounts.id')
            ->get($social.'_posts')
            ->row();
	}
	public function post_validator(){
		$accounts = post("accounts");
		if(empty($accounts)){
			ms(array(
				"status" => "error",
				"message" => lang("please_select_an_account")
			));
		}
		
		$directory = APPPATH.'../public/';
		$scanned_directory = array_diff(scandir($directory), array('..', '.'));
		$errors = array();
		$social_post = array();
		
		foreach ($scanned_directory as $key => $directory) {
			if(strpos($directory, ".") === false){
				$model_name = $directory."_model";
				$this->load->model($directory.'/'.$model_name, $model_name);
				
				$methods = get_class_methods($this->$model_name);
				if(in_array("post_validator", $methods)){
					$result = $this->$model_name->post_validator();
					if($result != 0){
						$errors[$directory] = $result;
						$social_post[] = $directory; 
					}
				}
			}
		}
		
		$have_errors = false;
		$can_post = false;
		$html_errors = "";
		$count_errors = 0;
		$social_can_posts = array();
		if(!empty($errors)){
			foreach ($errors as $social => $sub_errors) {
				if(empty($sub_errors)){
					$can_post = true;
					$social_can_posts[] = $social;
				}else{
					$have_errors = true;
					
					foreach ($sub_errors as $key => $error) {
						$html_errors .= "<li>{$error}</li>";
					}
					$count_errors++;
				}
			}
		}
		
		$html_errors = "<p>{$count_errors} ".lang("profiles_will_be_excluded_from_your_publication_in_next_step_due_to_errors")." </p><ul>".$html_errors."</ul>";
		
		$message = "";
		$status = "";
		if(!$have_errors){
			$status = "success";
		}else{
			if($can_post){
				$status = "warning";
			}else{
				$status = "error";
				$message = lang("missing_content_on_the_following_social_networks").implode(", ", $social_post);
				var_dump(array(
					"status"   => $status,
					"errors"   => $html_errors,
					"message"  => $message,
					"can_post" => json_encode($social_can_posts) 
				));
				die("here");
			}
		}
		

		return array(
			"status"   => $status,
			"errors"   => $html_errors,
			"message"  => $message,
			"can_post" => json_encode($social_can_posts) 
		);
	}

	public function post_handler($social_can_posts){
		$directory = APPPATH.'../public/';
		$scanned_directory = array_diff(scandir($directory), array('..', '.'));

		$result = array();
		foreach ($scanned_directory as $key => $directory) {
			if(strpos($directory, ".") === false){
				$model_name = $directory."_model";
				
				$this->load->model($directory.'/'.$model_name, $model_name);
				
				$methods = get_class_methods($this->$model_name);
				if(in_array("post_handler", $methods) && in_array($directory, $social_can_posts)){
					$result[$model_name] = $this->$model_name->post_handler();
				}
			}
		}
		$count_error = 0;
		$count_success = 0;
		if(!empty($result)){
			foreach ($result as $data) {
				foreach ($data as $item) {
					if(isset($item['status']) && $item['status'] == "success"){
						$count_success++;
					}else{
						$count_error++;
						
					}
				}
			}
		}

		if(!post("is_schedule")){
			if($count_error == 0){
				
				ms(array(
					"status"  => "success",
					"message" => sprintf(lang("Content is being published on %d profiles"), $count_success)
				));
			}elseif($count_success == 0){
				
				ms(array(
					"status"  => "success",
					"message" => sprintf(lang("Content unpublished on %d profiles"), $count_error)
				));
			}else{
				
				ms(array(
					"status"  => "success",
					"message" => sprintf(lang("Content is being published on %d profiles and %d profiles unpublished"), $count_success, $count_error)
				));
			}
		}else{
			
			ms(array(
				"status"  => "success",
				"message" => lang("Content successfully scheduled")
			));
		}
	} 

	public function post_previewer($link_info){

		//pr(permission_list(),1);
		$accounts = post("accounts");

		$directory = APPPATH.'../public/';
		$scanned_directory = array_diff(scandir($directory), array('..', '.'));
		$result = array();
		foreach ($scanned_directory as $key => $directory) {

			if( permission( $directory.'/post' ) ){
				$model_name = $directory."_model";
				if(strpos($directory, ".") === false && file_exists(APPPATH."../public/".$directory."/models/".$model_name.".php")){
					$this->load->model($directory.'/'.$model_name, $model_name);
					
					$methods = get_class_methods($this->$model_name);
					if(in_array("post_previewer", $methods)){
						$result[] = $this->$model_name->post_previewer($link_info);
					}
				}
			}

		}
		return $result;
	}


	public function get_caption($id) {
		$this->db->select('*');
		$this->db->from(CAPTION);
		$this->db->where('ids', $id);
		$query = $this->db->get();
		$caption =  $query->row();
		if($caption) {
			return $caption;
		}
		return false;	
	}
	function getList($table, $columns, $limit=-1, $page=-1,$status = null){
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
		$this->db->where('uid', user_or_cm());
		if($status){
			$this->db->where('status', $status);
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

	function getPosts($table){
		$c = (int)get_secure('c'); //Column key
		$t = get_secure('t'); //Sort type
		$k = get_secure('k'); //Search keywork
		
		$this->db->select('*');
		
		$this->db->from($table);

		//$this->db->where('uid', user_or_cm());
		//$this->db->where('status', $status);
		$this->db->order_by('created', 'desc');
		$query = $this->db->get();
		$result =  $query->result();
		return $result;
	}
}
