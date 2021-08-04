<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class dashboard extends MX_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');
	}

	public function index(){
		$data = array();
		$cond='';
		$isManager = is_manager();
		$isresponsable = is_responsable();
		$fbData = $this->model->getlastpost_fb();
		$igData = $this->model->getlastpost_ig();
		$twData = $this->model->getlastpost_tw();
		$data['lastpost_fb'] = $fbData;
		$data['lastpost_tw'] = $twData;
		$data['lastpost_ig'] = $igData;

		$data['countnotResp'] =$this->model->getCountnotRole($role='responsable');
		$data['countnotManagers'] =$this->model->getCountnotRole($role='manager');
		$data['countnotCustomers'] =$this->model->getCountnotRole($role='customer');

		$data['countManagers'] =$this->model->getCountRole($role='manager',$status=1);
		$data['countAdmins'] =$this->model->getCountRole($role='admin',$status=1);
		$data['countCustomers'] =$this->model->getCountRole($role='customer',$status=1);
		$data['countResp'] =$this->model->getCountRole($role='responsable',$status=1);


		$data['countManagersD'] =$this->model->getCountRole($role='manager',$status=0);
		$data['countAdminsD'] =$this->model->getCountRole($role='admin',$status=0);
		$data['countCustomersD'] =$this->model->getCountRole($role='customer',$status=0);
		$data['countRespD'] =$this->model->getCountRole($role='responsable',$status=0);

		$template = 'customer';
		$directory = APPPATH.'../public/';
		$scanned_directory = array_diff(scandir($directory), array('..', '.'));
		// var_dump($scanned_directory);die();
		$pie = "[";
		$user__id ="";
		if(!is_admin()){
			$user__id = user_or_cm();
		}
		$socials = get_count_posts_socials($user__id,ST_PUBLISHED);
		foreach ($scanned_directory as $key => $directory) {
				$pie .= $this->model->getCountPosts_permonths($directory.'_posts',$user__id,ST_PUBLISHED).",";
		}
		$pie =substr($pie, 0, -1)."]";
		$data["pie"]=$pie;
		$data["socials"]=$socials;
		if(is_admin()) {
			$template = 'admin';
		} elseif(is_manager()) {
			$uid = session("uid");
			$user = $this->model->get("*", USERS, "id = '".session("uid")."'");	
			$groupes = $this->model->getGRoupes($user->ids);	
			$template = 'manager';
		} elseif(is_responsable()) {
			$uid = session("uid");
			$user = $this->model->get("*", USERS, "id = '".session("uid")."'");	
			$groupes = $this->model->getGRoupes($user->ids);	
			$template = 'responsable';
		} else {
			// var_dump( session("uid"));die();
			$data['f_posts'] = $this->model->getCountPosts('facebook_posts', session("uid"));
			$data['f_posts_planified'] = $this->model->getCountPosts('facebook_posts', session("uid"), ST_PLANIFIED);
			$data['f_posts_published'] = $this->model->getCountPosts('facebook_posts', session("uid"), ST_PUBLISHED);
			$data['f_posts_failed'] = $this->model->getCountPosts('facebook_posts', session("uid"), ST_FAILED);
			$data['f_posts_waiting'] = $this->model->getCountPosts('facebook_posts', session("uid"), ST_WAITTING);
			$data['f_posts_draft'] = $this->model->getCountPosts('facebook_posts', session("uid"), ST_DRAFT);


			$data['t_posts'] = $this->model->getCountPosts('twitter_posts', session("uid"));
			$data['t_posts_planified'] = $this->model->getCountPosts('twitter_posts', session("uid"), ST_PLANIFIED);
			$data['t_posts_published'] = $this->model->getCountPosts('twitter_posts', session("uid"), ST_PUBLISHED);
			$data['t_posts_failed'] = $this->model->getCountPosts('twitter_posts', session("uid"), ST_FAILED);
			$data['t_posts_waiting'] = $this->model->getCountPosts('twitter_posts', session("uid"), ST_WAITTING);
			$data['t_posts_draft'] = $this->model->getCountPosts('twitter_posts', session("uid"), ST_DRAFT);


			$data['i_posts'] = $this->model->getCountPosts('instagram_posts', session("uid"));
			$data['i_posts_planified'] = $this->model->getCountPosts('instagram_posts', session("uid"), ST_PLANIFIED);
			$data['i_posts_published'] = $this->model->getCountPosts('instagram_posts', session("uid"), ST_PUBLISHED);
			$data['i_posts_failed'] = $this->model->getCountPosts('instagram_posts', session("uid"), ST_FAILED);
			$data['i_posts_waiting'] = $this->model->getCountPosts('instagram_posts', session("uid"), ST_WAITTING);
			$data['i_posts_draft'] = $this->model->getCountPosts('instagram_posts', session("uid"), ST_DRAFT);
		}
		if(is_manager() || is_responsable()) {
			$data['users'] = getUsersByRolelast3_month($from='acceuil');  
		}else{
			$data['users'] = getUsersByRole($from='acceuil');  
		}
		$data['count_standard'] = $this->model->getCountPack('standard');
		$data['count_premium'] = $this->model->getCountPack('premium');
		$data['count_trial'] = $this->model->getCountPack('starter');
		$data["packages"]=$this->model->getCountPackGroupName();
		


		$this->template->title('Dashboard');
		$this->template->build($template, $data);
	}

	public function remove_view_user(){
		login_as_user(session("uid"), true);

		ms(array(
			"status" => "success",
			"message" => lang("Back to admin successfully")
		));
	}
	
	public function report(){
		echo block_report(segment(3))->data;
	}
	public function get_report_by_date(){
		$date_from = post('date_from');
		$date_to = post('date_to');
		$reports = get_schedules_report( 2,$date_from,$date_to);
		$directory = APPPATH.'../public/';
		$scanned_directory = array_diff(scandir($directory), array('..', '.'));
		$pie = "[";
		foreach ($scanned_directory as $key => $directory) {
			$pie .= $this->model->getCountPosts_permonths($directory.'_posts',$userid,ST_PUBLISHED).",";
		}
		$pie =substr($pie, 0, -1)."]";
		$userid = "";
		if(!is_admin()){
			$userid = user_or_cm();
		}
		$socials = get_count_posts_socials($userid,ST_PUBLISHED);
		ms(array(
			"status" => "success",
			"successed" => $reports,
			"pie" => $pie,
			"socials" => $socials
		));
		
	}
	
}