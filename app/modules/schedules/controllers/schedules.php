<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class schedules extends MX_Controller {
	public $table;
	public $columns;
	public $module_name;
	public $module_icon;
	private $userid;

	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');
		$this->userid = user_or_cm();
		
		$this->tb_analytics = "instagram_analytics";
		$this->tb_analytics_stats = "instagram_analytics_stats";
		if(!is_customer()){
			$this->accounts = $this->model->fetch("instagram_accounts".".*",array("instagram_accounts",USERS), "instagram_accounts".'.uid = '.USERS.'.id and '.USERS.'.status=1');
			$this->accounts_fb = $this->model->fetch("facebook_accounts.*", array('facebook_accounts',USERS),'facebook_accounts.uid = '.USERS.'.id and '.USERS.'.status=1');
		}else{
			$this->accounts = $this->model->fetch("*", "instagram_accounts", "uid = '".session("uid")."'");
			$this->accounts_fb = $this->model->fetch("*", 'facebook_accounts', "uid = '".session("uid")."'");
		}
	}

	public function index(){
		$this->load->model('schedules_model');
		$inputs = $_GET;
		$data = array();
		$data['schedules'] = $this->schedules_model->get_all_schedules($inputs);
		if(count($inputs) > 0) {
			$data['sc_type'] = isset($inputs['sc_type']) ? $inputs['sc_type'] : '';
			$data['social_filter'] = isset($inputs['social_filter']) ? $inputs['social_filter'] : '';
			$data['date_from'] = isset($inputs['date_from']) ? $inputs['date_from'] : '';
			$data['date_to'] = isset($inputs['date_to']) ? $inputs['date_to'] : '';
		}
		if(is_manager() || is_admin() || is_responsable()){
			if(session("cm_uid")){
				$this->template->build('index', $data);
			}else{
				$this->template->build('empty');
			}
			
			
		} else{
			$this->template->build('index', $data);
		}
	}

	public function list_calendar(){
		$this->load->model('schedules_model');
		$inputs = $_GET;
		$data = array();
		$data['schedules'] = $this->schedules_model->get_all_schedules_cal_list($inputs);
		$data['grps'] = $this->schedules_model->get_groups();
		if(count($inputs) > 0) {
			$data['sc_type'] = isset($inputs['sc_type']) ? $inputs['sc_type'] : '';
			$data['social_filter'] = isset($inputs['social_filter']) ? $inputs['social_filter'] : '';
			$data['date_from'] = isset($inputs['date_from']) ? $inputs['date_from'] : '';
			$data['grps_filter'] = isset($inputs['grps_filter']) ? $inputs['grps_filter'] : '';
			$data['account_filter'] = isset($inputs['af']) ? $inputs['af'] : '';
		}
		$this->template->build('list', $data);
	}

	public function calendar(){
		$type = post("sc_type");
		$socials = $this->input->post("social_filter");
		$data = array(
			"type" => $type,
			"socials" =>  $socials
		);
		$this->load->view('calendar', $data);
	}
	
	
	public function get_embed_html_insta($code){
		echo schedules_model::get_embed_html($code);
	}
	public function listPostFBCrone(){
		$this->db->select('facebook_accounts.*');
		$this->db->from('facebook_accounts ');
		$this->db->join('user_information as user','user.id_user= facebook_accounts.uid' );
		$query = $this->db->get();
		$listaccounts =  $query->result();
		$resultat=array();
		$html="
		<p>Log Crone Post Faceboock : ".date("Y-m-d h:i:s")."</p>
		<table>
			<thead>
				<tr>
					<th>Account</th>
					<th>Caption</th>
					<th>Url</th>
					<th>Media</th>
					<th>Video</th>
					<th>Date creation Poste</th>
				</tr>
			</thead>
			<tbody>";
        foreach($listaccounts as $account){
			$user_account = $this->model->get("*", USERS, "id = '" . $account->uid . "'");
            try {
				$fb = new FacebookAPI();
				$token = $account->access_token;
				$fb->set_access_token($token);
				$fbId = $account->pid;
                $url = '/' . $fbId . '?fields=access_token';
                $token_insights = $fb->get($url);
				$token = $token_insights->access_token;
				
                $fb2 = new FacebookAPI();
				$fb2->set_access_token($token);
				// ?since=".strtotime("15+march+2021")."&until=".strtotime("30+march+2021")."
                $posts = $fb2->get('/' . $fbId . "/feed?fields=admin_creator,attachments,id,message,permalink_url,created_time")->data;
				if($posts!=null){
					foreach ($posts as $key => $post) {
						if($post->admin_creator->link!="https://idinfluencer.com/" && $post->admin_creator->link!="https://espaceclient.idinfluencer.com/"){
							$images=array();
							$video="";
							if($post->attachments!=null){
								$video=$post->attachments->data[0]->media->source;
								array_push($images,$post->attachments->data[0]->media->image->src);
								foreach($post->attachments->data[0]->subattachments->data as $sub){
									array_push($images,$sub->media->image->src);
								}
							}
							$post_exsists=$this->model->get("*", "posts_faceboock_api", "id_account = '{$account->pid}' and id_post= '{$post->id}'");
							if($post_exsists==null){
								$data_post=array(
									"id_account"=>$account->pid,
									"id_post"=>$post->id,
									"message"=>$post->message,
									"permalink_url"=>$post->permalink_url,
									"media"=>json_encode($images),
									"video"=>json_encode($video),
									"created_time"=>$post->created_time,
									"created"     => date("Y-m-d h:i:s"),
									// "fullname"=>$post->fullname,
									// "admin_creator"=>$post->admin_creator,
									// "attachments"=>$post->attachments
									// "creator"=>$post->admin_creator->link
								);
					
								$this->db->insert("posts_faceboock_api", $data_post);
								$html.="<tr>
									<td>".$account->fullname."</td>
									<td>".$post->message."</td>
									<td>".$post->permalink_url."</td>
									<td>".json_encode($images)."</td>
									<td>".json_encode($video)."</td>
									<td>".$post->created_time."</td>
								</tr>";
								array_push($resultat,$data_post);
							}else{
								$data_post=array(
									"message"=>$post->message,
									"permalink_url"=>$post->permalink_url,
									"media"=>json_encode($images),
									"video"=>json_encode($video),
									// "fullname"=>$post->fullname,
									// "admin_creator"=>$post->admin_creator,
									// "attachments"=>$post->attachments
									// "creator"=>$post->admin_creator->link
								);
								$this->db->update("posts_faceboock_api", $data_post,array("id_post"=>$post->id));
								$html.="<tr>
									<td>".$account->fullname."</td>
									<td>".$post->message."</td>
									<td>".$post->permalink_url."</td>
									<td>".json_encode($images)."</td>
									<td>".json_encode($video)."</td>
									<td>".$post->created_time."</td>
								</tr>";
								array_push($resultat,$data_post);

							}
						}
					}
				}
				
				// echo "<pre>";var_dump($resultat);die();
            } catch (Exception $e) {
			}
			
		}
		
		$html.="</tbody>
		</table>";
		// echo "<pre>";var_dump($resultat);die();
		$this->model->send_email_crone($html);
		echo "end crone";
        // return $resultat;
	}
	
	public function getlistProfileInstagram(){
		$user = $this->model->get("id,ids,role", "general_users", "id = '".session("uid")."' ");

		$this->db->select('instagram_accounts.*');
		$this->db->from('instagram_accounts ');
		$this->db->join('user_information as user','user.id_user= instagram_accounts.uid' );
		
		if ($user->role == "customer") {
			$this->db->join(USERS, USERS.".id=user.id_user");
			$this->db->where(USERS.".id",$user->id);

		}elseif($user->role=="manager"){
			$this->db->join(USERS, USERS.".id=user.id_user");
			$this->db->join('customer_cm','customer_cm.id_customer = '.USERS.'.id' );
			$this->db->where('customer_cm.id_manager',$user->id);
		}elseif($user->role=="responsable"){
			$this->db->join(USERS, USERS.".id=user.id_user");
			$this->db->join('customer_cm','customer_cm.id_customer = '.USERS.'.id' );
			$this->db->join('responsable_cm','responsable_cm.id_manager = customer_cm.id_manager' );
			$this->db->where('responsable_cm.id_responsable',$user->id);
		}
		$query = $this->db->get();
		$listaccounts =  $query->result();
		echo json_encode($listaccounts,true);
	}
	public function getFeedInstagram($ids){
		$resultat=array();
		// $this->db->select('instagram_accounts.*');
		// $this->db->from('instagram_accounts ');
		// $this->db->where('ids',$ids);
		
		// $account = $this->db->get();
		
		$account = $this->model->get("*", 'instagram_accounts', "ids = '" . $ids . "'");
		$ids=$account->ids;
		$account = $this->model->get("*", "instagram_accounts", "ids = '".$ids."'");
		$proxy_data = get_proxy("instagram_accounts", $account->proxy, $account);
		try {
			$ig = new InstagramAPI($account->username, $account->password, $proxy_data->use);
			// var_dump($ig);die();
			$result=$ig->analytics->process();
			// echo "<pre>";var_dump($result->list_posts);die();	
			$resultat[] =["username"=>$account->username,"list_posts"=>$result->list_posts];
		} catch (Exception $e) {
			die("err");
		}
	
		echo json_encode($resultat,true);
	}
	public function ListPostInstagram(){
		$this->template->build('list_post_in');
	
	}
	// -----------------------------------------------------------------------------
	public function listPostInstaCrone(){
		
		$listaccounts=array();

		$this->db->select('instagram_accounts.*');
		$this->db->from('instagram_accounts ');
		$this->db->join('user_information as user','user.id_user= instagram_accounts.uid' );
		$query = $this->db->get();
		$listaccounts =  $query->result();
		$resultat=array();
		foreach($listaccounts as $account){
			$ids=$account->ids;
			$account = $this->model->get("*", "instagram_accounts", "ids = '".$ids."'");

			
			// $proxy_data = get_proxy("instagram_accounts", $account->proxy, $account);
			// try {
			// 	$ig = new InstagramAPI($account->username, $account->password, $proxy_data->use);
			// 	// var_dump($ig);die();
			// 	$result=$ig->analytics->process();
			// 	// echo "<pre>";var_dump($result->list_posts);die();	
			// 	$resultat[] =["username"=>$account->username,"list_posts"=>$result->list_posts];
			// } catch (Exception $e) {
			// 	die("err");
			// }
		}
		// echo "<pre>";var_dump($resultat);die();
		return $resultat;
	
	}
	public function listPostFB($inputs=null){
		$sc_type = isset($inputs['sc_type']) ? $inputs['sc_type'] : '';
		$social_filter = isset($inputs['social_filter']) ? $inputs['social_filter'] : '';
		$date_from = isset($inputs['date_from']) ? $inputs['date_from'] : '';
		$grps_filter = isset($inputs['grps_filter']) ? $inputs['grps_filter'] : '';
		$account_filters = isset($inputs['af']) ? $inputs['af'] : '';
		$list_account_fliters=array();
		$listaccounts=array();

		$year="";
		$month="";
		if($date_from!=""){

			$year=explode("/",$date_from)[1];		
			$month=explode("/",$date_from)[0];
		}else{
			$year=date("Y");
			$month=date("m");
		}
		$first_date="01-$month-$year";
		$last_day_month=date("Y-m-t", strtotime($first_date));
		$first_date_month=date("Y-m-d", strtotime($first_date));
		// echo "<pre>";var_dump($first_date,$last_day_month,$first_date_month);die();



		$user = $this->model->get("id,ids,role", "general_users", "id = '".session("uid")."'");
		// var_dump($user,$account_filters);die();
		if ($account_filters!="") {
			foreach($account_filters as $account_filter){
				$list_account_fliters[]=explode("_",$account_filter)[1];
			}
		}else{
            $list_accounts=$this->schedules_model->get_groups1();
            foreach($list_accounts as $list_account){
                $twitters=$list_account["twitter"];
                foreach($twitters as $twitter){
                    if($twitter!=null){
                        // var_dump($twitter[0]);die();
                        $list_account_fliters[] = $twitter[0]->id;
                    }
                }
                $instagrams=$list_account["instagram"];
                foreach($instagrams as $instagram){
                    if($instagram!=null){
                        $list_account_fliters[] = $instagram[0]->id;
                    }
                }
                $facebooks=$list_account["facebook"];
                foreach($facebooks as $facebook){
                    if($facebook!=null){
                        $list_account_fliters[] = $facebook[0]->id;
                    }
                }
            }
        }
		$this->db->select('facebook_accounts.*');
		$this->db->from('facebook_accounts ');
		$this->db->join('user_information as user','user.id_user= facebook_accounts.uid' );
	
		if ($user->role == "customer") {
			$this->db->join(USERS, USERS.".id=user.id_user");
			$this->db->where(USERS.".id",$user->id);

		}elseif($user->role=="manager"){
			$this->db->join(USERS, USERS.".id=user.id_user");
			$this->db->join('customer_cm','customer_cm.id_customer = '.USERS.'.id' );
			$this->db->where('customer_cm.id_manager',$user->id);
		}elseif($user->role=="responsable"){
			$this->db->join(USERS, USERS.".id=user.id_user");
			$this->db->join('customer_cm','customer_cm.id_customer = '.USERS.'.id' );
			$this->db->join('responsable_cm','responsable_cm.id_manager = customer_cm.id_manager' );
			$this->db->where('responsable_cm.id_responsable',$user->id);
		}
		if($list_account_fliters!=null){
			$this->db->where_in("facebook_accounts.id",$list_account_fliters);
		}
		// if($user->role=="customer"){
		// 	if($account_filters!=""){
		// 		$this->db->where("id = '".session("uid")."'");
		// 	}
		// }
		$query = $this->db->get();
		$listaccounts =  $query->result();

        $resultat=array();
        foreach($listaccounts as $account){
			$user_account = $this->model->get("*", USERS, "id = '" . $account->uid . "'");
			// var_dump($account);die();
			
			$fb = new FacebookAPI();
            $token = $account->access_token;
            $fb->set_access_token($token);
            $fbId = $account->pid;
			// var_dump($fbId, $account->access_token);die();
            try {
                $url = '/' . $fbId . '?fields=access_token';
                $token_insights = $fb->get($url);
                $token = $token_insights->access_token;
                $fb2 = new FacebookAPI();
				$fb2->set_access_token($token);
				$condition_time="";
				// $condition_time="since=".$first_date_month."&until=".$last_day_month."&";
				$posts = $fb2->get('/' . $fbId . "/feed?fields=admin_creator,attachments,id,message,permalink_url,created_time");
				foreach ($posts->data as $key => $post) {
                    if($post->admin_creator->link!="https://idinfluencer.com/" && $post->admin_creator->link!="https://espaceclient.idinfluencer.com/"){
                        $images=array();
                        $video="";
                        if($post->attachments!=null){
                            $video=$post->attachments->data[0]->media->source;
                            array_push($images,$post->attachments->data[0]->media->image->src);
                            foreach($post->attachments->data[0]->subattachments->data as $sub){
                                array_push($images,$sub->media->image->src);
                            }
                        }
                        $data_post=array(
							"ids"=>$user_account->ids,
							"id"=>$account->id,
							"avatar"=>$account->avatar,
                            "message"=>$post->message,
                            "permalink_url"=>$post->permalink_url,
                            "media"=>$images,
                            "video"=>$video,
                            "id"=>$post->id,
							"fullname"=>$post->fullname,
							"created_time"=>$post->created_time,
							"admin_creator"=>$post->admin_creator,
                            // "attachments"=>$post->attachments
							// "creator"=>$post->admin_creator->link
                        );
                        array_push($resultat,$data_post);
                    }
                }
            } catch (Exception $e) {
            }
		}
		// echo "<pre>";var_dump($resultat);die();
        return $resultat;
	}
	public function listPostFBApi($inputs=null){
		$sc_type = isset($inputs['sc_type']) ? $inputs['sc_type'] : '';
		$social_filter = isset($inputs['social_filter']) ? $inputs['social_filter'] : '';
		$date_from = isset($inputs['date_from']) ? $inputs['date_from'] : '';
		$grps_filter = isset($inputs['grps_filter']) ? $inputs['grps_filter'] : '';
		$account_filters = isset($inputs['af']) ? $inputs['af'] : '';
		$list_account_fliters=array();
		$listaccounts=array();
		$year="";
		$month="";
		if($date_from!=""){

			$year=explode("/",$date_from)[1];		
			$month=explode("/",$date_from)[0];
		}else{
			$year=date("Y");
			$month=date("m");
		}
		$first_date="01-$month-$year";
		$last_day_month=date("Y-m-t", strtotime($first_date));
		$first_date_month=date("Y-m-d", strtotime($first_date));
		// echo "<pre>";var_dump($first_date,$last_day_month,$first_date_month);die();

		$user = $this->model->get("id,ids,role", "general_users", "id = '".session("uid")."'");
		// var_dump($user,$account_filters);die();
		if ($account_filters!="") {
			foreach($account_filters as $account_filter){
				$list_account_fliters[]=explode("_",$account_filter)[1];
			}
		}else{
            $list_accounts=$this->schedules_model->get_groups1();
            foreach($list_accounts as $list_account){
                $twitters=$list_account["twitter"];
                foreach($twitters as $twitter){
                    if($twitter!=null){
                        // var_dump($twitter[0]);die();
                        $list_account_fliters[] = $twitter[0]->id;
                    }
                }
                $instagrams=$list_account["instagram"];
                foreach($instagrams as $instagram){
                    if($instagram!=null){
                        $list_account_fliters[] = $instagram[0]->id;
                    }
                }
                $facebooks=$list_account["facebook"];
                foreach($facebooks as $facebook){
                    if($facebook!=null){
                        $list_account_fliters[] = $facebook[0]->id;
                    }
                }
            }
        }
		$this->db->select('facebook_accounts.fullname,facebook_accounts.url,facebook_accounts.avatar,posts_faceboock_api.*');
		$this->db->from('facebook_accounts ');
		$this->db->join('user_information as user','user.id_user= facebook_accounts.uid' );
		$this->db->join('posts_faceboock_api','posts_faceboock_api.id_account = facebook_accounts.pid' );
	
		if ($user->role == "customer") {
			$this->db->join(USERS, USERS.".id=user.id_user");
			$this->db->where(USERS.".id",$user->id);

		}elseif($user->role=="manager"){
			$this->db->join(USERS, USERS.".id=user.id_user");
			$this->db->join('customer_cm','customer_cm.id_customer = '.USERS.'.id' );
			$this->db->where('customer_cm.id_manager',$user->id);
		}elseif($user->role=="responsable"){
			$this->db->join(USERS, USERS.".id=user.id_user");
			$this->db->join('customer_cm','customer_cm.id_customer = '.USERS.'.id' );
			$this->db->join('responsable_cm','responsable_cm.id_manager = customer_cm.id_manager' );
			$this->db->where('responsable_cm.id_responsable',$user->id);
		}
		if($list_account_fliters!=null){
			$this->db->where_in("facebook_accounts.id",$list_account_fliters);
		}
		// if($user->role=="customer"){
		// 	if($account_filters!=""){
		// 		$this->db->where("id = '".session("uid")."'");
		// 	}
		// }
		$query = $this->db->get();
		$listposts =  $query->result();
        return $listposts;
	}
	public function listPostInsta($inputs=null){
		$sc_type = isset($inputs['sc_type']) ? $inputs['sc_type'] : '';
		$social_filter = isset($inputs['social_filter']) ? $inputs['social_filter'] : '';
		$date_from = isset($inputs['date_from']) ? $inputs['date_from'] : '';
		$grps_filter = isset($inputs['grps_filter']) ? $inputs['grps_filter'] : '';
		$account_filters = isset($inputs['af']) ? $inputs['af'] : '';
		$list_account_fliters=array();
		$listaccounts=array();
		$user = $this->model->get("id,ids,role", "general_users", "id = '".session("uid")."' ");
		foreach($account_filters as $account_filter){
			$list_account_fliters[]=explode("_",$account_filter)[1];
		}

		$this->db->select('instagram_accounts.avatar,instagram_accounts.username,instagram_post_crone.*');
		$this->db->from('instagram_accounts ');
		$this->db->join('instagram_post_crone ','instagram_post_crone.account_id = instagram_accounts.pid' );
		$this->db->join('user_information as user','user.id_user= instagram_accounts.uid' );
		
		if ($user->role == "customer") {
			$this->db->join(USERS, USERS.".id=user.id_user");
			$this->db->where(USERS.".id",$user->id);

		}elseif($user->role=="manager"){
			$this->db->join(USERS, USERS.".id=user.id_user");
			$this->db->join('customer_cm','customer_cm.id_customer = '.USERS.'.id' );
			$this->db->where('customer_cm.id_manager',$user->id);
		}elseif($user->role=="responsable"){
			$this->db->join(USERS, USERS.".id=user.id_user");
			$this->db->join('customer_cm','customer_cm.id_customer = '.USERS.'.id' );
			$this->db->join('responsable_cm','responsable_cm.id_manager = customer_cm.id_manager' );
			$this->db->where('responsable_cm.id_responsable',$user->id);
		}
		if($list_account_fliters!=null){
			$this->db->where_in("instagram_accounts.id",$list_account_fliters);
		}
		$query = $this->db->get();
		$listaccounts =  $query->result();
		return $listaccounts;
	
	}
	public function calendar_user_test(){
		$this->load->model('schedules_model');
		// $type = post("sc_type");
		// $socials = $this->input->post("social_filter");
		// $data = array(
		// 	"type" => $type,
		// 	"socials" =>  $socials
		// );
		$inputs = $_GET;
		$data = array();
		$user = $this->model->get("*",USERS, "id = ".session("uid"));
		if (count($inputs) > 0 || is_customer()) {
			$data['schedules'] = $this->schedules_model->get_all_schedules_cal1($inputs);
		}else{
			$data['schedules']=array();
		}
		$data['grps'] = $this->schedules_model->get_groups1();
		
		$data['user_role'] = $user;
		if (count($inputs) > 0  || is_customer()) {
			$posts=$this->listPostFB($inputs);
			$data['posts'] = $posts;
		}else{
			$posts=array();
			$data['posts'] = $posts;
		}
		
		// if (count($inputs) > 0 || is_customer()) {
		// 	$posts_insta=$this->listPostInsta($inputs);
		// 	$data['posts_insta'] = $posts_insta;
		// }else{
		// 	$posts_insta=array();
		// 	$data['posts_insta'] = $posts_insta;
		// }
		$data['posts_insta'] = array();

		if(count($inputs) > 0) {
			$data['sc_type'] = isset($inputs['sc_type']) ? $inputs['sc_type'] : '';
			$data['social_filter'] = isset($inputs['social_filter']) ? $inputs['social_filter'] : '';
			$data['date_from'] = isset($inputs['date_from']) ? $inputs['date_from'] : '';
			$data['grps_filter'] = isset($inputs['grps_filter']) ? $inputs['grps_filter'] : '';
			$data['account_filter'] = isset($inputs['af']) && $inputs['af']!=NULL ? $inputs['af'] : '';
		}
		$this->template->build('list_post_insta',$data);
	}
	public function calendar_user(){
		set_time_limit(-1);
		// error_reporting(-1);
		// ini_set('display_errors', 1);
		$this->load->model('schedules_model');
		// $type = post("sc_type");
		// $socials = $this->input->post("social_filter");
		// $data = array(
		// 	"type" => $type,
		// 	"socials" =>  $socials
		// );
		$inputs = $_GET;
		// echo "<pre>";var_dump($inputs["sc_type"]);die();
		$data = array();
		$date_from = isset($inputs['date_from']) ? $inputs['date_from'] : '';
		// var_dump($date_from);die();
		$user = $this->model->get("*",USERS, "id = ".session("uid"));
		if (count($inputs) > 0 || is_customer()) {
			$data['schedules'] = $this->schedules_model->get_all_schedules_cal1($inputs);
			// echo "<pre>";var_dump($data['schedules']);die();
		}else{
			$data['schedules']=array();
		}
		$data['grps'] = $this->schedules_model->get_groups1();
		
		$data['user_role'] = $user;
		if (is_customer() || in_array("Facebook",$inputs["social_filter"])) {
			$posts=$this->listPostFBAPI($inputs);
			// $posts=array();

			$data['posts'] = $posts;
		}else{
			$posts=array();
			$data['posts'] = $posts;
		}
		
		if (is_customer() || in_array("Instagram",$inputs["social_filter"])) {
			$posts_insta=array();
			$posts_insta=$this->listPostInsta($inputs);
			$data['posts_insta'] = $posts_insta;
		}else{
			$posts_insta=array();
			$data['posts_insta'] = $posts_insta;
		}

		if(count($inputs) > 0) {
			$data['sc_type'] = isset($inputs['sc_type']) ? $inputs['sc_type'] : '';
			$data['social_filter'] = isset($inputs['social_filter']) ? $inputs['social_filter'] : '';
			$data['date_from'] = isset($inputs['date_from']) ? $inputs['date_from'] : '';
			$data['grps_filter'] = isset($inputs['grps_filter']) ? $inputs['grps_filter'] : '';
			$data['account_filter'] = isset($inputs['af']) && $inputs['af']!=NULL ? $inputs['af'] : '';
		}
		// echo "<pre>";print_r($data);die();
		$this->template->build('list_post_test',$data);
	}
	public function calendar_user_old(){
		$this->load->model('schedules_model');
		// $type = post("sc_type");
		// $socials = $this->input->post("social_filter");
		// $data = array(
		// 	"type" => $type,
		// 	"socials" =>  $socials
		// );
		$inputs = $_GET;
		$data = array();
		$user = $this->model->get("*",USERS, "id = ".session("uid"));
		$data['schedules'] = $this->schedules_model->get_all_schedules_cal($inputs);
		$data['grps'] = $this->schedules_model->get_groups();

		$data['user_role'] = $user;
		// $posts=$this->listPost($inputs);
		// $data['posts'] = $posts;
		if(count($inputs) > 0) {
			$data['sc_type'] = isset($inputs['sc_type']) ? $inputs['sc_type'] : '';
			$data['social_filter'] = isset($inputs['social_filter']) ? $inputs['social_filter'] : '';
			$data['date_from'] = isset($inputs['date_from']) ? $inputs['date_from'] : '';
			$data['grps_filter'] = isset($inputs['grps_filter']) ? $inputs['grps_filter'] : '';
			$data['account_filter'] = isset($inputs['af']) && $inputs['af']!=NULL ? $inputs['af'] : '';
		}
		// dump($data['account_filter']);
		$this->template->build('calendartwo',$data);
	}

	public function delete(){
		$type = post("sc_delete_type");
		$social = post("sc_delete_social");

		if(empty($social)){
			ms(array(
				"status" => "error",
				"message" => lang("Please select social network you want delete schedules")
			));
		}

		delete_schedules($type, $social);

		ms(array(
        	"status"  => "success"
        ));
	}

	public function xml($type = "queue", $socials = ""){
		header("Content-type: text/xml");
		block_schedules($type, $socials);
	}

	/****************************************/
	/*           SCHEDULES POST             */
	/****************************************/
	public function block_schedules_xml($template = "", $tb_posts = "", $type = ""){
		$this->load->model('schedules_model');

		$this->load->library('user_agent');
		if($this->agent->browser() != ""){
			redirect(cn());
		}

		switch ($type) {
			case 'published':
				$filter = "?t=2";
				break;

			case 'unpublished':
				$filter = "?t=3";
				break;
			
			default:
				$filter = "";
				break;
		}

		$result = $this->schedules_model->get_calendar_schedules($tb_posts, $type);
		$data = "";
		if(!empty($result)){
			foreach ($result as $key => $row) {
				$module = "post";
				$test = "hhhh'hhhh";

				if(isset($template['module'])){
					$module = $template['module'];
				}

				$data .= '<event>
				    <id>'.ids().'</id>
				    <name>	&lt;i class="'.$template['icon'].'" &gt; &lt;/i &gt; '.$row->total.' '.$template['name'].'</name>
				    <startdate>'.$row->time_post.'</startdate>
				    <enddate></enddate>
				    <color>'.$template['color'].'</color>
					<url>'.PATH.$template['controller']."/".$module."/schedules/".date("Y/m/d", strtotime($row->time_post)).$filter.'</url>
					<content><div class="">'.$test.'</div></content>
				  </event>';
			}
		}else{
			return false;
		}

		print_r($data);
	}

	public function schedules($username = "", $tb_posts = "", $tb_accounts = ""){
		$this->load->model('schedules_model');
		$year = segment(4);
		$month = segment(5);
		$day = segment(6);
		$date = $year."/".$month."/".$day;
		if(!validateDate($date, "Y/m/d")){
			redirect(cn("schedules"));
		}else{
			set_session("schedule_date", str_replace("/", "-", $date));
		}

		$data = array(
			'accounts' => $this->schedules_model->count_post_on_each_account($username, $tb_posts, $tb_accounts),
			'date' => $date,
			'count_status' => $this->schedules_model->count_schedules($tb_posts)
		);

		if (!$this->input->is_ajax_request()) {
			$view = $this->load->view("../../../app/modules/schedules/views/general", $data, true);
			$this->template->build('../../../app/modules/schedules/views/schedules', array("view" => $view));
		}else{
			$this->load->view("../../../app/modules/schedules/views/general", $data);
		}
	}

	public function ajax_schedules($username = "", $tb_posts = "", $tb_accounts = ""){
		if (!$this->input->is_ajax_request()) {
			redirect(cn());
		}

		$this->load->model('schedules_model');
		$data = array(
			'schedules' => $this->schedules_model->get_schedules((int)post("page"), $username, $tb_posts, $tb_accounts),
			'page' => (int)post("page")
		);

		$this->load->view('ajax_schedules', $data);
	}

	public function ajax_delete_schedules($tb_posts = "", $delete_all = false, $type = ""){
		if (!$this->input->is_ajax_request()) {
			redirect(cn());
		}

		$this->load->model('schedules_model');

		if($delete_all){
			switch ($type) {
				case 'queue':
					
					$type = 1;
					break;

				case 'published':
					$type = 2;
					break;

				case 'unpublished':
					$type = 3;
					break;
				
				default:
					$type = 0;
					break;
			}

			$this->db->delete($tb_posts, array("uid" => $this->userid, "status" => $type));
		}else{

			if($tb_posts != ""){
				$ids = post("id");

				if($ids == -1){
					$this->db->delete($tb_posts, array(
						"uid" => $this->userid, 
						"time_post >=" => get_timezone_system(session("schedule_date")." 00:00:00"),
						"time_post <=" => get_timezone_system(session("schedule_date")." 23:59:59"),
						"status" => session("schedule_type")
					));
				}else{
					$this->db->delete($tb_posts, array("uid" => $this->userid, "ids" => $ids ));
				}

				ms(array(
		        	"status"  => "success",
		        	"message" => lang('delete_successfully')
		        ));
			}
		}
	}

	//****************************************/
	//         END SCHEDULES POST            */
	//****************************************/
	
}