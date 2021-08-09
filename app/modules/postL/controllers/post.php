<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class post extends MX_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');
		$this->module_name = lang("post");
		$this->module_icon = "fas fa-clock";
		$this->columns = array(
			"type"               => lang("type"),
			"data"               => lang("data"),
			"social_label"       => "",
			"id"                 => lang("id"),
			"uid"                 => lang("uid"),
			"status"                 => lang("status"),
			"time_post"          => lang("date"),
		);
	}

	public function facebook() {
		$data = array();
		$page        = (int)get("p");
		$limit       = 10;
		$result      = $this->model->getList("facebook_posts", $this->columns, $limit, $page);
		$total       = $this->model->getList("facebook_posts", $this->columns, -1, -1);
		$total_final = $total;

		$query = array();
		$query_string = "";
		if(get("c")) $query["c"] = get("c");
		if(get("t")) $query["t"] = get("t");
		if(get("k")) $query["k"] = get("k");

		if(!empty($query)){
			$query_string = "?".http_build_query($query);
		}

		$configs = array(
			"base_url"   => cn(get_class($this).'/facebook'.$query_string), 
			"total_rows" => $total_final, 
			"per_page"   => $limit
		);

		$this->pagination->initialize($configs);

		$data = array(
			"columns" => $this->columns,
			"posts"  => $result,
			"total"   => $total_final,
			"page"    => $page,
			"limit"   => $limit,
			"module"  => get_class($this),
			"module_name" => $this->module_name,
			"module_icon" => $this->module_icon
		);
		$data['type']= 'Facebook';
		$this->template->build('list_posts', $data);
	}
	public function instagram() {
		$data = array();
		$page        = (int)get("p");
		$limit       = 10;
		$result      = $this->model->getList("instagram_posts", $this->columns, $limit, $page);
		$total       = $this->model->getList("instagram_posts", $this->columns, -1, -1);
		$total_final = $total;

		$query = array();
		$query_string = "";
		if(get("c")) $query["c"] = get("c");
		if(get("t")) $query["t"] = get("t");
		if(get("k")) $query["k"] = get("k");

		if(!empty($query)){
			$query_string = "?".http_build_query($query);
		}

		$configs = array(
			"base_url"   => cn(get_class($this).'/instagram'.$query_string), 
			"total_rows" => $total_final, 
			"per_page"   => $limit
		);

		$this->pagination->initialize($configs);

		$data = array(
			"columns" => $this->columns,
			"posts"  => $result,
			"total"   => $total_final,
			"page"    => $page,
			"limit"   => $limit,
			"module"  => get_class($this),
			"module_name" => $this->module_name,
			"module_icon" => $this->module_icon
		);
		$data['type']= 'Instagram';
		$this->template->build('list_posts', $data);
	}
	public function twitter() {
		$data = array();
		$page        = (int)get("p");
		$limit       = 10;
		$result      = $this->model->getList("twitter_posts", $this->columns, $limit, $page);
		$total       = $this->model->getList("twitter_posts", $this->columns, -1, -1);
		$total_final = $total;

		$query = array();
		$query_string = "";
		if(get("c")) $query["c"] = get("c");
		if(get("t")) $query["t"] = get("t");
		if(get("k")) $query["k"] = get("k");

		if(!empty($query)){
			$query_string = "?".http_build_query($query);
		}

		$configs = array(
			"base_url"   => cn(get_class($this).'/twitter'.$query_string), 
			"total_rows" => $total_final, 
			"per_page"   => $limit
		);

		$this->pagination->initialize($configs);

		$data = array(
			"columns" => $this->columns,
			"posts"  => $result,
			"total"   => $total_final,
			"page"    => $page,
			"limit"   => $limit,
			"module"  => get_class($this),
			"module_name" => $this->module_name,
			"module_icon" => $this->module_icon
		);
		$data['posts'] = $this->model->getPosts('twitter_posts');
		$data['type']= 'Twitter';

		$this->template->build('list_posts', $data);
	}

	public function index(){
		$accounts = $this->model->get_accounts();
		$id_caption = isset($_GET['id_caption']) ? $_GET['id_caption'] : '';
		$content = '';
		$caption = "";
		if(!empty($id_caption)) {
			$caption = $this->model->get_caption($id_caption);
		}
		if($caption) {
			$content = $caption->content;
		}
		$data = array(
			"accounts" => $accounts,
			"content" => $content
		);
		if(get('pid') && get('social')){
			if(get('draft')){
				$data['draft'] = $this->model->getpost_info(get('pid'),get('social'));
			}else{
				$data['updateP'] = $this->model->getpost_info(get('pid'),get('social'));
			}
		}
		if(is_manager() || is_admin() || is_responsable()){
			if(session("cm_uid")){
				if(empty($data["accounts"])){
					$accounts = $this->model->get_accounts();
					$data["accounts"]=$accounts;
				}
				if(!empty($data['updateP'])){
					$cond="";
					if(get('social')==""){
						$cond="AND official != 3";
					}
					$accounts = $this->model->get("*", get('social')."_accounts", "status = 1 AND ids = '".$data['updateP']->pid."' ".$cond);
					$user = $this->model->get("*", USERS, "id = '".$accounts->uid."'");
					if(!empty($user)){
						unset_session("cm_uid");
						set_session('cm_uid',$user->id);
						$this->userid=user_or_cm();
						$accounts = $this->model->get_accounts();
						$data["accounts"]=$accounts;
					}
				}
				$this->template->build('index', $data);
			}elseif(!empty($data['updateP'])){
				if(is_manager() || is_admin() || is_responsable()){
					$cond="";
					if(get('social')==""){
						$cond="AND official != 3";
					}
					$accounts = $this->model->get("*", get('social')."_accounts", "status = 1 AND ids = '".$data['updateP']->pid."' ".$cond);
					$user = $this->model->get("*", USERS, "id = '".$accounts->uid."'");
					if(!empty($user)){
						unset_session("cm_uid");
						set_session('cm_uid',$user->id);
						$this->userid=user_or_cm();
						$accounts = $this->model->get_accounts();
						$data["accounts"]=$accounts;
					}
					$this->template->build('index', $data);
				}
			}else{
				echo "3";exit;
				$this->template->build('empty');
			}
		} else{
			if(permission_pack("create_a_post")){
				$this->template->build('index', $data);
			}else{
				redirect(cn("dashboard"));
			}
		}
	}

	public function draft(){
				$page        = (int)get("p");
		$limit       = 20;
		$result = [];
		$status = ST_INVALID;


		$result_facebook   = $this->model->getList('facebook_posts', $this->columns, $limit, $page,ST_DRAFT);
		$result_twitter      = $this->model->getList('twitter_posts', $this->columns, $limit, $page,ST_DRAFT);
		$result_instagram      = $this->model->getList('instagram_posts', $this->columns, $limit, $page,ST_DRAFT);

		$total_facebook       = $this->model->getList('facebook_posts', $this->columns, -1, -1,ST_DRAFT);
		$total_twitter       = $this->model->getList('twitter_posts', $this->columns, -1, -1,ST_DRAFT);
		$total_instagram       = $this->model->getList('instagram_posts', $this->columns, -1, -1,ST_DRAFT);
		

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
		if(is_manager() || is_admin() || is_responsable()){
			if(session("cm_uid")){
				$this->template->build('draft', $data);
			}else{
				$this->template->build('empty');
			}
		} else{
			$this->template->build('draft', $data);
		}		
	}

	public function ajax_post($skip_validate = false){
		$typeOfSubmit = isset($_POST['typeOfSubmit']) ? $_POST['typeOfSubmit'] : '';
		$validator = array();
		$validator["status"] = "success";
		$validator["can_post"] ='[';
		if($typeOfSubmit == 'draft'){
			$accounts = post("accounts");
			if(empty($accounts)){
				ms(array(
					"status" => "error",
					"message" => lang("please_select_an_account")
				));
			}
			$skip_validate =true;
			foreach (post('accounts') as $key => $value) {
				list($acc,$ids) = explode('-',$value);
				$validator["can_post"] .='"'.$acc.'",';
			}
			$validator["can_post"] = rtrim($validator["can_post"], ",").']';
		}else{
			$validator = $this->model->post_validator();
		}
		$social_can_post = json_decode($validator["can_post"]);
		$check_max_posts = check_number_post();
		if(!$check_max_posts){
			ms(array(
				"status" => "error",
				"message" => lang("solde de publication insuffisant")
			));
		}
		
		if( (($skip_validate && !empty($social_can_post)) || $validator["status"] == "success") ){
			$result = $this->model->post_handler($social_can_post);
		}

		ms($result);
	}

	public function get_link_info(){
		$link = post("link");
		$link_info = get_info_link($link);
		ms($link_info);
	}

	public function previewer(){
		//Get link info
		$link = post("link");
		$link_info = (object)get_info_link($link);

		$result = $this->model->post_previewer($link_info);
		$data = array(
			"link_info" => $link_info,
			"result" => $result
		);

		echo $this->load->view("preview", $data, true);
	}
}