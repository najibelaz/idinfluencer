<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class dashboard_model extends MY_Model {
	public function __construct(){
		parent::__construct();
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

		$this->db->where("uid = '".session("uid")."'");

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


	function getlastpost_fb(){
		return $this->db->select('data, fullname, avatar,time_post,facebook_posts.type as type')
			->where("facebook_posts.uid",session('uid'))
			->where("facebook_posts.status",ST_PUBLISHED)
			->order_by('facebook_posts.id',"desc")
            ->limit(1)
			->join('facebook_accounts', 'facebook_posts.account=facebook_accounts.id')
			->get('facebook_posts')
            ->row();
	}
	function getlastpost_ig(){
		
		return $this->db->select('data, username, avatar,time_post,instagram_posts.type as type')
			->where("instagram_posts.uid",session('uid'))
			->where("instagram_posts.status",ST_PUBLISHED)
			->order_by('instagram_posts.id',"desc")
			->limit(1)
			->join('instagram_accounts', 'instagram_posts.account=instagram_accounts.id')
            ->get('instagram_posts')
            ->row();
	}
	function getlastpost_tw(){
		
		return $this->db->select('data, username, avatar,time_post,twitter_posts.type as type')
			->where("twitter_posts.uid",session('uid'))
			->where("twitter_posts.status",ST_PUBLISHED)
			->order_by('twitter_posts.id',"desc")
			->limit(1)
			->join('twitter_accounts', 'twitter_posts.account=twitter_accounts.id')
            ->get('twitter_posts')
            ->row();
	}

	function getCountRole($role='admin', $status=1) {
		$user = $this->model->get("id,ids,role", USERS, "id = '".session('uid')."'");
		$this->db->select('count(DISTINCT ids) as sum');
		$this->db->from(USERS);
		// if($user->role == "manager" || $user->role == "responsable"){
		// 	$this->db->join('user_group', USERS.'.ids = user_group.id_user');
		// 	$this->db->join('manager_group', 'user_group.id_group = manager_group.id_group');
		// 	if($user->role == "responsable"){
		// 		$this->db->join('responsable_group', 'manager_group.id_group = responsable_group.id_group');
		// 		$this->db->where("responsable_group.id_user ='".$user->ids."'");
		// 	}else{
		// 		$this->db->where("manager_group.id_user ='".$user->ids."'");
		// 	}
		// }
		if($user->role=="manager"){
			$this->db->join('customer_cm','customer_cm.id_customer = '.USERS.'.id' );
			$this->db->where('customer_cm.id_manager',$user->id);
		}elseif($user->role=="responsable"){
			$this->db->join('customer_cm','customer_cm.id_customer = '.USERS.'.id' );
			$this->db->join('responsable_cm','responsable_cm.id_manager = customer_cm.id_manager' );
			$this->db->where('responsable_cm.id_responsable',$user->id);
		}
		$this->db->where(USERS.'.role',$role);
		$this->db->where(USERS.'.status',$status);

		$this->db->order_by('created','desc');
		$query = $this->db->get();
		return $query->row()->sum;
	}
	function getCountnotRole($role='admin') {
		$user = $this->model->get("ids,role", USERS, "id = '".session('uid')."'");
		$this->db->select('count(DISTINCT ids) as sum');
		$this->db->from(USERS);
		if($role == "customer"){
			$this->db->where("ids NOT IN (SELECT id_user FROM user_group)");

		}else{
			// $this->db->join($role.'_group', USERS.'.ids != '.$role.'_group.id_user','LEFT');
			$this->db->where("ids NOT IN (SELECT id_user FROM {$role}_group)");
		}
			
		$this->db->where("role in ('".$role."')");
		$query = $this->db->get();
		return $query->row()->sum;
	}

	function getCountPosts($table, $uid="", $status='') {
		$this->db->select('count(*) as sum');
		$this->db->from($table);
		if(!empty($uid)){
			$this->db->where("uid =".$uid);
		}
		$this->db->where("MONTH(time_post) = MONTH(CURRENT_DATE())");
		if(!empty($status)) {
			$this->db->where("status =".$status);
		}
		//$this->db->order_by('created','desc');
		$query = $this->db->get();
		return $query->row()->sum;
	}
	function getCountPosts_permonths($table, $uid="", $status='') {
		$date_from=post('date_from');
		$date_to=post('date_to');
		$this->db->select('count(*) as sum');

		if(isset($date_from) && !empty($date_from)){
			$date_from = strtotime(str_replace('/', '-', $date_from ));
			$newDate = date("Y-m-d", $date_from);
			$this->db->where("time_post >= '".$newDate."' ");

		}
		if(isset($date_to) && !empty($date_to)){
			$date_to = strtotime(str_replace('/', '-', $date_to ));
			$newDate = date("Y-m-d", $date_to);
			$this->db->where("time_post <= '".$newDate."' ");
		}
		if(empty($date_to)  && empty($date_from)){
			$this->db->where("(MONTH(time_post) = MONTH(CURRENT_DATE()) OR MONTH(time_post) = MONTH(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH)))");
		}
		$this->db->from($table);
		if(!empty($uid)){
			$this->db->where("uid =".$uid);
		}
		if(!empty($status)) {
			$this->db->where("status =".$status);
		}
		//$this->db->order_by('created','desc');
		$query = $this->db->get();
		return $query->row()->sum;
	}

	function getCountPack($package='premium') {
		$this->db->select('count(*) as sum');
		$this->db->from(USERS);
		if($package == 'premium') {
			$packId = PACK_PREMIUM;
		} elseif($package == 'standard') {
			$packId = PACK_STANDARD;
		} elseif($package == 'starter') {
			$packId = PACK_STARTER;
		}

		$this->db->where("package =".$packId);
		$this->db->where("role like 'customer' ");
		$this->db->order_by('created','desc');
		$query = $this->db->get();
		return $query->row()->sum;
	}
	function getCountPackGroupName() {
		$user = $this->model->get("id,ids,role", USERS, "id = '".user_or_cm()."'");
		$this->db->select('p.name,count( *) as sum');
		$this->db->from('general_packages as p');
		$this->db->join(USERS, USERS.'.package=p.id');

		// if($user->role=="manager" || $user->role=="responsable"){
		// 	$this->db->join('user_group as groups_user','groups_user.id_user = u.ids' );
		// 	$this->db->join($user->role.'_group as groups','groups.id_group = groups_user.id_group' );
		// 	$this->db->where('groups.id_user',$user->ids);
		// }
		
		if($user->role=="manager"){
			$this->db->join('customer_cm','customer_cm.id_customer = '.USERS.'.id' );
			$this->db->where('customer_cm.id_manager',$user->id);
		}elseif($user->role=="responsable"){
			$this->db->join('customer_cm','customer_cm.id_customer = '.USERS.'.id' );
			$this->db->join('responsable_cm','responsable_cm.id_manager = customer_cm.id_manager' );
			$this->db->where('responsable_cm.id_responsable',$user->id);
		}

		$this->db->where(USERS.".status=1");
		// $this->db->where("package!='' ");
		// $this->db->where("role ='customer'");
		$this->db->group_by("p.name");

		// $this->db->order_by('created','desc');
		$query = $this->db->get();
		return $query->result();
	}

	function getUsers($limit = -1, $page=0, $isManager=false,$isresponsable = false){

		if($limit == -1){
			$this->db->select('count(*) as sum');
		}else{
			$this->db->select('*');
		}
		
		$this->db->from(USERS);

		/*if($limit != -1) {
			$this->db->limit($limit,$page);
		}*/
		if($isresponsable) {
			$this->db->where("role like 'customer'");
			$this->db->or_where("role like 'manager'");

		}

		if($isManager) {
			$this->db->where("role like 'customer'");
			//$this->db->where("package =".$packId);
		}
		
		$this->db->order_by('created','desc');
		$query = $this->db->get();

		if($query && $query->result()){
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

	function getGRoupes($ids) {
		$this->db->select('*');
		$this->db->from(GROUPES_MANAGER);
		$query = $this->db->get();
		$result =  $query->result();

		if($result){
			return $result;
		} else{
			return false;
		}
	}

}
