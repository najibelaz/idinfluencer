<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class waiting extends MX_Controller {
	public $table;
	public $columns;
	public $module_name;
	public $module_icon;

	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');

		//Config Module
		$this->table       = 'WAITING';
		$this->module_name = lang("waiting");
		$this->module_icon = "ft-command";
		$table= "ff";
		$this->columns = array(
			"type"               => lang("type"),
			"data"               => lang("data"),
			"social_label"       => "",
			"time_post"          => lang("date"),
		);
	}

	public function index(){
		$page        = (int)get("p");
		$limit       = 20;
		$result = [];
		$status = ST_WAITTING;

		$result_facebook   = $this->model->getListt('facebook_posts', $this->columns, $limit, $page,$status);
		$result_twitter      = $this->model->getListt('twitter_posts', $this->columns, $limit, $page,$status);
		$result_instagram      = $this->model->getListt('instagram_posts', $this->columns, $limit, $page,$status);

		$total_facebook       = $this->model->getListt('facebook_posts', $this->columns, -1, -1,$status);
		$total_twitter       = $this->model->getListt('twitter_posts', $this->columns, -1, -1,$status);
		$total_instagram       = $this->model->getListt('instagram_posts', $this->columns, -1, -1,$status);
		

		$total_final = $total_facebook + $total_twitter + $total_instagram;

		if($result_facebook) {
			foreach ($result_facebook as $data) {
				$result[] = $data;
			}
		}
		if($result_twitter) {
			foreach ($result_twitter as $data) {
				$result[] = $data;
			}
		}
		if($result_instagram) {
			foreach ($result_instagram as $data) {
				$result[] = $data;
			}
		}
		
		$query = array();
		$query_string = "";
		if(get("c")) $query["c"] = get("c");
		if(get("t")) $query["t"] = get("t");
		if(get("k")) $query["k"] = get("k");

		if(!empty($query)){
			$query_string = "?".http_build_query($query);
		}

		$configs = array(
			"base_url"   => cn(get_class($this).$query_string), 
			"total_rows" => $total_final, 
			"per_page"   => $limit
		);

		$this->pagination->initialize($configs);
		$data = array(
			"columns" => $this->columns,
			"result"  => $result,
			"total"   => $total_final,
			"page"    => $page,
			"limit"   => $limit,
			"module"  => get_class($this),
			"module_name" => $this->module_name,
			"module_icon" => $this->module_icon
		);
		if(is_manager() ){
			if(session("cm_uid")){
				$this->validated();
			}else{
				$this->template->build('empty');
			}
		} else{
			if(is_admin() || is_responsable()){
				$this->template->build('index', $data);
			}
		}
	}
	public function validated(){
		$page        = (int)get("p");
		$limit       = 20;
		$result = [];
		$status = ST_PLANIFIED;
		
		$result_twitter      = $this->model->getListt('twitter_posts', $this->columns, $limit, $page,$status);
		$result_facebook   = $this->model->getListt('facebook_posts', $this->columns, $limit, $page,$status);
		$result_instagram      = $this->model->getListt('instagram_posts', $this->columns, $limit, $page,$status);
		
		$total_facebook       = $this->model->getListt('facebook_posts', $this->columns, -1, -1,$status);
		$total_twitter       = $this->model->getListt('twitter_posts', $this->columns, -1, -1,$status);
		$total_instagram       = $this->model->getListt('instagram_posts', $this->columns, -1, -1,$status);
		

		$total_final = $total_facebook + $total_twitter + $total_instagram;

		if($result_facebook) {
			foreach ($result_facebook as $data) {
				$result[] = $data;
			}
		}
		if($result_twitter) {
			foreach ($result_twitter as $data) {
				$result[] = $data;
			}
		}
		if($result_instagram) {
			foreach ($result_instagram as $data) {
				$result[] = $data;
			}
		}
		
		$query = array();
		$query_string = "";
		if(get("c")) $query["c"] = get("c");
		if(get("t")) $query["t"] = get("t");
		if(get("k")) $query["k"] = get("k");

		if(!empty($query)){
			$query_string = "?".http_build_query($query);
		}

		$configs = array(
			"base_url"   => cn(get_class($this).$query_string), 
			"total_rows" => $total_final, 
			"per_page"   => $limit
		);

		$this->pagination->initialize($configs);
		$data = array(
			"columns" => $this->columns,
			"result"  => $result,
			"total"   => $total_final,
			"page"    => $page,
			"limit"   => $limit,
			"module"  => get_class($this),
			"module_name" => $this->module_name,
			"module_icon" => $this->module_icon
		);
		if(session("cm_uid")){
			$this->template->build('validated', $data);
		}else{
			$this->template->build('empty');
		}
	}
	public function invalidated(){
		$page        = (int)get("p");
		$limit       = 20;
		$result = [];
		$status = ST_INVALID;
		$this->columns['return_reason'] = 'return_reason';

		$result_facebook   = $this->model->getListt('facebook_posts', $this->columns, $limit, $page,$status);
		$result_twitter      = $this->model->getListt('twitter_posts', $this->columns, $limit, $page,$status);
		$result_instagram      = $this->model->getListt('instagram_posts', $this->columns, $limit, $page,$status);

		$total_facebook       = $this->model->getListt('facebook_posts', $this->columns, -1, -1,$status);
		$total_twitter       = $this->model->getListt('twitter_posts', $this->columns, -1, -1,$status);
		$total_instagram       = $this->model->getListt('instagram_posts', $this->columns, -1, -1,$status);
		

		$total_final = $total_facebook + $total_twitter + $total_instagram;

		if($result_facebook) {
			foreach ($result_facebook as $data) {
				$result[] = $data;
			}
		}
		if($result_twitter) {
			foreach ($result_twitter as $data) {
				$result[] = $data;
			}
		}
		if($result_instagram) {
			foreach ($result_instagram as $data) {
				$result[] = $data;
			}
		}
		
		$query = array();
		$query_string = "";
		if(get("c")) $query["c"] = get("c");
		if(get("t")) $query["t"] = get("t");
		if(get("k")) $query["k"] = get("k");

		if(!empty($query)){
			$query_string = "?".http_build_query($query);
		}

		$configs = array(
			"base_url"   => cn(get_class($this).$query_string), 
			"total_rows" => $total_final, 
			"per_page"   => $limit
		);

		$this->pagination->initialize($configs);
		$data = array(
			"columns" => $this->columns,
			"result"  => $result,
			"total"   => $total_final,
			"page"    => $page,
			"limit"   => $limit,
			"module"  => get_class($this),
			"module_name" => $this->module_name,
			"module_icon" => $this->module_icon
		);

		// $this->template->build('index', $data);
		if(session("cm_uid")){
			$this->template->build('invalidated', $data);
		}else{
			$this->template->build('empty');
		}
	}

	public function add(){
		$this->template->build('add');
	}

	public function ajax_update(){
		$ids         = post("ids");
		$caption     = post("caption");

		if($caption == ""){
			ms(array(
				"status"  => "error",
				"message" => lang("caption_is_required")
			));
		}

		$data = array(
			'uid'       => user_or_cm(),
			'content'   => $caption,
			'changed'   => NOW
		);

		$item = $this->model->get("id", $this->table, "ids = '{$ids}'");
		if(empty($item)){
			$data['ids'] = ids();
			$data['status'] = 1;
			$data['created'] = NOW;
			$this->db->insert($this->table, $data);
		}else{
			$this->db->update($this->table, $data, array("id" => $item->id));
		}
		
		ms(array(
			"status"  => "success",
			"message" => lang("successfully")
		));
	}

	public function export(){
		export_csv($this->table);
	}

	public function ajax_update_status_valid(){
		$pid = post('pid');
		$status = ST_PLANIFIED;
		$social = post('social');
		
		$update = $this->model->update_status_post($pid,$status,$social);
		if($update){
			
			ms(array(
				"status"  => "success",
				"message" => lang("update_successfully")
			));
			
		}else{

			ms(array(
				"status"  => "error",
				"message" => lang("Cannot update status, please try again")
			));

		}
		
		
	}
	public function ajax_update_status_invalid(){
		$pid = post('pid');
		$status = ST_INVALID;
		$social = post('social');
		$reason = post('reason');
		$update = $this->model->update_status_post($pid,$status,$social,$reason);
		if($update){
			
			ms(array(
				"status"  => "success",
				"message" => lang("update_successfully")
			));
			
		}else{

			ms(array(
				"status"  => "error",
				"message" => lang("Cannot update status, please try again")
			));

		}
		
		
	}
	public function ajax_update_status_delete(){
		$pid = post('pid');
		$status = ST_DELETED;
		$social = post('social');
		$reason = post('reason');
		$update = $this->model->update_status_post($pid,$status,$social,$reason);
		if($update){
			
			ms(array(
				"status"  => "success",
				"message" => lang("update_successfully")
			));
			
		}else{

			ms(array(
				"status"  => "error",
				"message" => lang("Cannot update status, please try again")
			));

		}
		
		
	}
	public function ajax_update_status(){
		get('status');
		$this->model->update_status($this->table, post("id"), false);
		$this->index();
	}

	public function popup(){
		$this->load->view('popup_caption');
	}

	public function save_caption(){
		$caption = post("caption");

		$item = $this->model->get("*", $this->table, "content = '{$caption}' AND uid = '".user_or_cm()."'");
		if(empty($item)){
			$data = array(
				"ids" => ids(),
				"uid" => user_or_cm(),
				"content" => $caption,
				"status" => 1,
				"changed" => NOW,
				"created" => NOW
			);

			$this->db->insert($this->table, $data);

			ms(array(
			"status"  => "success",
			"message" => lang("add_caption_successfully")
		));
		}else{
			ms(array(
				"status"  => "error",
				"message" => lang("this_caption_already_exists")
			));
		}
	}

	public function get_caption($page = 0){
		$limit = 10;
		$start = $page * $limit;
		$next_start = ($page+1) * $limit;
		$captions = $this->model->fetch("*", $this->table, "status = 1 AND uid = '".user_or_cm()."'", "id", "desc", $start, $limit);
		$next_captions = $this->model->fetch("*", $this->table, "status = 1 AND uid = '".user_or_cm()."'", "id", "desc", $next_start, $limit);
		$data = array(
			"limit" => $limit,
			"page" => $page,
			"captions" => $captions,
			"next_captions" => $next_captions
		);

		$this->load->view('get_caption', $data);
	}



	// Cron waitting to planified
	public function cron_fb(){
		$limit = 100000;
		$status = ST_PLANIFIED;
		$page        = (int)get("p");
		$result_facebook   = $this->model->getListt('facebook_posts', $this->columns, $limit, $page,$status);
		foreach ($result_facebook as $key => $value) {
			# code...
			$now = time(); // or your date as well
			$your_date = strtr($value->time_post, '/', '-');
			$your_date = strtotime($your_date);
			$datediff =  $your_date - $now;
			$datediff = round($datediff / (60 * 60 * 24));
			if($datediff > 3){
				$data["status"] = ST_WAITTING;
			}else{
				$data["status"] = ST_PLANIFIED;
			}
			$this->db->update($this->tb_posts, $data,"id=".$value->id);
		}	
	}
	// Cron waitting to planified
	public function cron_insta(){
		$limit = 100000;
		$status = ST_PLANIFIED;
		$page        = (int)get("p");
		$result_facebook   = $this->model->getListt('instagram_posts', $this->columns, $limit, $page,$status);
		foreach ($result_facebook as $key => $value) {
			# code...
			$now = time(); // or your date as well
			$your_date = strtr($value->time_post, '/', '-');
			$your_date = strtotime($your_date);
			$datediff =  $your_date - $now;
			$datediff = round($datediff / (60 * 60 * 24));
			if($datediff > 3){
				$data["status"] = ST_WAITTING;
			}else{
				$data["status"] = ST_PLANIFIED;
			}
			$this->db->update($this->tb_posts, $data,"id=".$value->id);
		}	
	}
	// Cron waitting to planified
	public function cron_twitter(){
		$limit = 100000;
		$status = ST_PLANIFIED;
		$page        = (int)get("p");
		$result_facebook   = $this->model->getListt('twitter_posts', $this->columns, $limit, $page,$status);
		foreach ($result_facebook as $key => $value) {
			# code...
			$now = time(); // or your date as well
			$your_date = strtr($value->time_post, '/', '-');
			$your_date = strtotime($your_date);
			$datediff =  $your_date - $now;
			$datediff = round($datediff / (60 * 60 * 24));
			if($datediff > 3){
				$data["status"] = ST_WAITTING;
			}else{
				$data["status"] = ST_PLANIFIED;
			}
			$this->db->update($this->tb_posts, $data,"id=".$value->id);
		}	
	}
}