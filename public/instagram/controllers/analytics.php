<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class analytics extends MX_Controller {
	public function __construct(){
		parent::__construct();
		
		$this->tb_accounts = INSTAGRAM_ACCOUNTS;
		$this->tb_analytics = "instagram_analytics";
		$this->tb_analytics_stats = "instagram_analytics_stats";
		$this->module = get_class($this);
		$this->module_name = lang("instagram_accounts");
		$this->module_icon = "fa fa-instagram";
		$this->load->model($this->module.'_model', 'model');




		if (is_customer()) {
            $this->accounts_fb= $this->model->fetch("*", "facebook_accounts", "uid = '" . session("uid") . "'");
            $this->accounts = $this->model->fetch("*", 'instagram_accounts', "uid = '" . session("uid") . "'");
        } else if(is_admin()) {
            $this->accounts_fb= $this->model->fetch( "facebook_accounts.*", array("facebook_accounts", USERS), "facebook_accounts.uid = " . USERS . '.id and ' . USERS . '.status=1 and role="customer"');
            $this->accounts = $this->model->fetch("instagram_accounts.*", array('instagram_accounts', USERS), 'instagram_accounts.uid = ' . USERS . '.id and ' . USERS . '.status=1  and role="customer"');
        }else{
			$users = getUsersByRole($from='acceuil');
			
            $list_user="(";
            foreach ($users as $user){
                    $list_user.="'".$user->ids."',";
            }
            $list_user=rtrim($list_user, ",");
            // var_dump($list_user);die();
            $list_user.=")";
            
            $this->accounts_fb= $this->model->fetch( "facebook_accounts.*", array("facebook_accounts", USERS), "facebook_accounts" . '.uid = ' . USERS . '.id and ' . USERS . '.status=1 and role="customer" and '.USERS.'.ids in '.$list_user);
			$this->accounts = $this->model->fetch("instagram_accounts.*", array('instagram_accounts', USERS), 'instagram_accounts.uid = ' . USERS . '.id and ' . USERS . '.status=1  and role="customer" and '.USERS.'.ids in '.$list_user);
        }

		// if(!is_customer()){
		// 	$this->accounts = $this->model->fetch(INSTAGRAM_ACCOUNTS.".*",array(INSTAGRAM_ACCOUNTS,USERS), INSTAGRAM_ACCOUNTS.'.uid = '.USERS.'.id and '.USERS.'.status=1');
		// 	$this->accounts_fb = $this->model->fetch("facebook_accounts.*", array('facebook_accounts',USERS),'facebook_accounts.uid = '.USERS.'.id and '.USERS.'.status=1');
		// }else{
		// 	$this->accounts = $this->model->fetch("*", INSTAGRAM_ACCOUNTS, "uid = '".session("uid")."'");
		// 	$this->accounts_fb = $this->model->fetch("*", 'facebook_accounts', "uid = '".session("uid")."'");
		// }
	}
	
	public function stats($ids = ""){
		// $ids = get('idsrs');
		$account = $this->model->get("*", $this->tb_accounts, "ids = '".$ids."'");

		if(empty($account)){
			$view = $this->load->view("analytics/ajax/empty", array(), true);
			return $this->template->build('analytics/index', array("view" => $view, "accounts" => $this->accounts,'accounts_fb' => $this->accounts_fb));
		}
		
		$facebook_app_id=get_option("facebook_app_id");
		$facebook_app_secret=get_option("facebook_app_secret");
		$fb = new newinstagramapi($facebook_app_id, $facebook_app_secret);
		$since="";
		$until="";
		if(get("date_from")!=""){
			$since = strtotime(str_replace('/', '-', get("date_from") ));
			$since = date("Y-m-d", $since);
		}
		if(get("date_to")!=""){
			$until = strtotime(str_replace('/', '-', get("date_to") ));
			$until = date("Y-m-d", $until);
		}
		if(get("date_from")==Null && get("date_to")==Null){
			$since = strtotime(str_replace('/', '-',"01/". date("m/Y") ));
			$since = date("Y-m-d", $since);
			
			$until = strtotime(str_replace('/', '-',date("d/m/Y") ));
			$until = date("Y-m-d", $until);
		}

		$insightProfile=$fb->insightProfile($account->password,$account->pid);
		
		$insight = $fb->rapport($account->password,$account->pid,"impressions, reach, follower_count, email_contacts, phone_call_clicks, text_message_clicks, get_directions_clicks, website_clicks, profile_views",$since,$until,"day");
		$data_insights_impressions=$insight[0];
		$table_impression_data="";
		$table_impression_date="";
		$count_impression=0;
		if ($data_insights_impressions != null) {
			foreach ($data_insights_impressions as $key => $impression) {
				if ($key == "values") {
					foreach ($impression as  $key_value => $value) {
						$count_impression += $value->value;
						$table_impression_data .= "'{$value->value}',";
						$date = date("d-m-Y", strtotime($value->end_time));
						$table_impression_date .= "'{$date}',";
					}
				}
			}
		}
		$data_insights_reachs=$insight[1];
		$table_reach_data="";
		$table_reach_date="";
		$count_reach=0;
		if ($data_insights_reachs != null) {
			foreach ($data_insights_reachs as $key => $reach) {
				if ($key == "values") {
					foreach ($reach as  $key_value => $value) {
						$count_reach += $value->value;
						$table_reach_data .= "'{$value->value}',";
						$date = date("d-m-Y", strtotime($value->end_time));
						$table_reach_date .= "'{$date}',";
					}
				}
			}
		}
		$data_insights_follower_counts=$insight[2];
		$table_follower_count_data="";
		$table_follower_count_date="";
		$count_follower_count=0;
		if ($data_insights_follower_counts != null) {
			foreach ($data_insights_follower_counts as $key => $follower_count) {
				if ($key == "values") {
					foreach ($follower_count as  $key_value => $value) {
						$count_follower_count += $value->value;
						$table_follower_count_data .= "'{$value->value}',";
						$date = date("d-m-Y", strtotime($value->end_time));
						$table_follower_count_date .= "'{$date}',";
					}
				}
			}
		}
		// $audience = $fb->rapport($account->password,$account->pid,"audience_gender_age, audience_locale, audience_country, audience_city","","","yesterday");
		$audience = $fb->rapport($account->password,$account->pid,"audience_gender_age,audience_locale,audience_country,audience_city","","","lifetime");
		
		$data_insights_audience_gender_ages=$audience[0];
		$table_audience_gender_age_data="";
		$table_audience_gender_age_date="";
		$count_audience_gender_age=0;
		if ($data_insights_audience_gender_ages != null) {
			foreach ($data_insights_audience_gender_ages as $key => $audience_gender_age) {
				if ($key == "values") {

					foreach ($audience_gender_age[0]->value as  $key_value => $value) {
						$count_audience_gender_age += $value;
						$table_audience_gender_age_data .= "'{$value}',";
						$date = date("d-m-Y", strtotime($value->end_time));
						$table_audience_gender_age_date .= "'{$key_value}',";
					}
				}
			}
		}
		
		$data_insights_audience_locales=$audience[1];
		$table_audience_locale_data="";
		$table_audience_locale_date="";
		$count_audience_locale=0;
		if ($data_insights_audience_locales != null) {
			foreach ($data_insights_audience_locales as $key => $audience_locale) {
				if ($key == "values") {
					foreach ($audience_locale[0]->value as  $key_value => $value) {
						$count_audience_locale += $value;
						$table_audience_locale_data .= "'{$value}',";
						$date = date("d-m-Y", strtotime($value->end_time));
						$table_audience_locale_date .= "'{$key_value}',";
					}
				}
			}
		}

		$data_insights_audience_countrys=$audience[2];
		$table_audience_country_data="";
		$table_audience_country_date="";
		$count_audience_country=0;
		if ($data_insights_audience_countrys != null) {
			foreach ($data_insights_audience_countrys as $key => $audience_country) {
				if ($key == "values") {
					foreach ($audience_country[0]->value as  $key_value => $value) {
						$count_audience_country += $value;
						$table_audience_country_data .= "'{$value}',";
						$date = date("d-m-Y", strtotime($value->end_time));
						$table_audience_country_date .= "'{$key_value}',";
					}
				}
			}
		}

		$data_insights_audience_citys=$audience[3];
		$table_audience_city_data="";
		$table_audience_city_date="";
		$count_audience_city=0;
		if ($data_insights_audience_citys != null) {
			foreach ($data_insights_audience_citys as $key => $audience_city) {
				if ($key == "values") {
					foreach ($audience_city[0]->value as  $key_value => $value) {
						$count_audience_city += $value;
						$table_audience_city_data .= "'{$value}',";
						$date = date("d-m-Y", strtotime($value->end_time));
						$table_audience_city_date .= "'".addslashes($key_value)."',";
					}
				}
			}
		}
		// echo "<pre>";var_dump($table_audience_gender_age_data,$table_audience_gender_age_date,$count_audience_gender_age);die();
		// $online_followers = $fb->rapport($ids,"online_followers",$since,$until);
		// $impressions = $fb->rapport($ids,"impressions",$since,$until);

		//Check Start First Time
		// $action_exist = $this->model->get("*", $this->tb_analytics, "account = '".$account->id."' AND '".session("uid")."'");
		// if(empty($action_exist)){
		// 	$proxy_data = get_proxy($this->tb_accounts, $account->proxy, $account);
		// 	try {
				
		// 		$ig = new InstagramAPI($account->username, $account->password, $proxy_data->use);
		// 		$result = $ig->analytics->process();
				
		// 		$user_timezone = get_timezone_user(NOW);
		// 		$user_day = date("Y-m-d", strtotime($user_timezone));

		// 		$check_stats_exist = $this->model->get("id", $this->tb_analytics_stats, " account = '".$account->id."' AND uid = '".session("uid")."' AND date = '".$user_day."'");
		// 		if(empty($check_stats_exist)){

		// 			//Save data
		// 			$user_data = array(
		// 				"media_count" => $result->userinfo->media_count,
		// 				"follower_count" => $result->userinfo->follower_count,
		// 				"following_count" => $result->userinfo->following_count,
		// 				"engagement" => $result->engagement
		// 			);

					
		// 			$data = array(
		// 				"ids" => ids(),
		// 				"uid" => session("uid"),
		// 				"account" => $account->id,
		// 				"data" => json_encode($user_data),
		// 				"date" => date("Y-m-d", strtotime($user_timezone))
		// 			);

		// 			$this->db->insert($this->tb_analytics_stats, $data);

		// 			$save_info = array(
		// 				"engagement" => $result->engagement,
		// 				"average_likes" => $result->average_likes,
		// 				"average_comments" => $result->average_comments,
		// 				"top_hashtags" => $result->top_hashtags,
		// 				"top_mentions" => $result->top_mentions,
		// 				"feeds" => $result->feeds,
		// 				"userinfo" => $result->userinfo,
		// 			);
					
		// 			//Next Action
		// 			$now = date('Y-m-d 00:00:00', strtotime($user_timezone));
		// 			$next_day = date('Y-m-d 00:00:00', strtotime($now) + 86400);
		// 			$data_next_action = array(
		// 				"ids" => ids(),
		// 				"uid" => session("uid"),
		// 				"account" => $account->id,
		// 				"data" => json_encode($save_info),
		// 				"next_action" => get_timezone_system($next_day)
		// 			);

		// 			$this->db->insert($this->tb_analytics, $data_next_action);
		// 		}
		// 	} catch (Exception $e) {
				
		// 	}

		// }
		$table_follower_count_data  = "[".substr($table_follower_count_data, 0, -1)."]";
		$table_follower_count_date  = "[".substr($table_follower_count_date, 0, -1)."]";

		$table_reach_data  = "[".substr($table_reach_data, 0, -1)."]";
		$table_reach_date  = "[".substr($table_reach_date, 0, -1)."]";

		$table_impression_data  = "[".substr($table_impression_data, 0, -1)."]";
		$table_impression_date  = "[".substr($table_impression_date, 0, -1)."]";
		
		$table_audience_gender_age_data  = "[".substr($table_audience_gender_age_data, 0, -1)."]";
		$table_audience_gender_age_date  = "[".substr($table_audience_gender_age_date, 0, -1)."]";
		
		$table_audience_locale_data  = "[".substr($table_audience_locale_data, 0, -1)."]";
		$table_audience_locale_date  = "[".substr($table_audience_locale_date, 0, -1)."]";
		
		$table_audience_city_data  = "[".substr($table_audience_city_data, 0, -1)."]";
		$table_audience_city_date  = "[".substr($table_audience_city_date, 0, -1)."]";
		
		$table_audience_country_data  = "[".substr($table_audience_country_data, 0, -1)."]";
		$table_audience_country_date  = "[".substr($table_audience_country_date, 0, -1)."]";
		
		$data = array(
			// "result" => $this->model->get_stats($ids),
			"account" => $account,
			"result"=>$insightProfile,
			"insightProfile"=>$insightProfile,

			"table_follower_count_data"=>$table_follower_count_data,
			"table_follower_count_date"=>$table_follower_count_date,
			"count_follower_count"=>$count_follower_count,

			"table_reach_data"=>$table_reach_data,
			"table_reach_date"=>$table_reach_date,
			"count_reach"=>$count_reach,

			"table_impression_data"=>$table_impression_data,
			"table_impression_date"=>$table_impression_date,
			"count_impression"=>$count_impression,

			"table_audience_gender_age_data"=>$table_audience_gender_age_data,
			"table_audience_gender_age_date"=>$table_audience_gender_age_date,
			"count_audience_gender_age"=>$count_audience_gender_age,

			
			"table_audience_locale_data"=>$table_audience_locale_data,
			"table_audience_locale_date"=>$table_audience_locale_date,
			"count_audience_locale"=>$count_audience_locale,

			
			"table_audience_country_data"=>$table_audience_country_data,
			"table_audience_country_date"=>$table_audience_country_date,
			"count_audience_country"=>$count_audience_country,

			
			"table_audience_city_data"=>$table_audience_city_data,
			"table_audience_city_date"=>$table_audience_city_date,
			"count_audience_city"=>$count_audience_city,

			
		);
		// echo "<pre>";var_dump($data);die();
		if (!$this->input->is_ajax_request()) {
			$view = $this->load->view("analytics/ajax/analytics", $data, true);
			$this->template->build('analytics/index', array("view" => $view, "accounts" => $this->accounts,'accounts_fb' => $this->accounts_fb));
		}else{
			$this->load->view("analytics/ajax/analytics", $data);
		}
		
	}
	public function index($ids = ""){
		$account = $this->model->get("*", $this->tb_accounts, "ids = '".$ids."'");
		if(empty($account)){
			$view = $this->load->view("analytics/ajax/empty", array(), true);
			return $this->template->build('analytics/index', array("view" => $view, "accounts" => $this->accounts,'accounts_fb' => $this->accounts_fb));
		}
		//Check Start First Time
		$action_exist = $this->model->get("*", $this->tb_analytics, "account = '".$account->id."' AND '".session("uid")."'");
		if(!empty($action_exist)){
			$proxy_data = get_proxy($this->tb_accounts, $account->proxy, $account);
			try {
				$facebook_app_id=get_option("facebook_app_id");
				$facebook_app_secret=get_option("facebook_app_secret");
				$fb = new newinstagramapi($facebook_app_id, $facebook_app_secret);
				// $fb->
				// $fb->rapport();
				// $ig = new InstagramAPI($account->username, $account->password, $proxy_data->use);
				// $result = $ig->analytics->process();
				// echo "<pre>";var_dump($result);die();
				$user_timezone = get_timezone_user(NOW);
				$user_day = date("Y-m-d", strtotime($user_timezone));
				
				$check_stats_exist = $this->model->get("id", $this->tb_analytics_stats, " account = '".$account->id."' AND uid = '".session("uid")."' AND date = '".$user_day."'");
				if(empty($check_stats_exist)){

					//Save data
					$user_data = array(
						"media_count" => $result->userinfo->media_count,
						"follower_count" => $result->userinfo->follower_count,
						"following_count" => $result->userinfo->following_count,
						"engagement" => $result->engagement
					);

					
					$data = array(
						"ids" => ids(),
						"uid" => session("uid"),
						"account" => $account->id,
						"data" => json_encode($user_data),
						"date" => date("Y-m-d", strtotime($user_timezone))
					);

					$this->db->insert($this->tb_analytics_stats, $data);

					$save_info = array(
						"engagement" => $result->engagement,
						"average_likes" => $result->average_likes,
						"average_comments" => $result->average_comments,
						"top_hashtags" => $result->top_hashtags,
						"top_mentions" => $result->top_mentions,
						"feeds" => $result->feeds,
						"userinfo" => $result->userinfo,
					);
					
					//Next Action
					$now = date('Y-m-d 00:00:00', strtotime($user_timezone));
					$next_day = date('Y-m-d 00:00:00', strtotime($now) + 86400);
					$data_next_action = array(
						"ids" => ids(),
						"uid" => session("uid"),
						"account" => $account->id,
						"data" => json_encode($save_info),
						"next_action" => get_timezone_system($next_day)
					);

					$this->db->insert($this->tb_analytics, $data_next_action);
				}
			} catch (Exception $e) {
				
			}

		}

		$data = array(
			"result" => $this->model->get_stats($ids),
			"account" => $account
		);	

		if (!$this->input->is_ajax_request()) {
			$view = $this->load->view("analytics/ajax/analytics", $data, true);
			$this->template->build('analytics/index', array("view" => $view, "accounts" => $this->accounts,'accounts_fb' => $this->accounts_fb));
		}else{
			$this->load->view("analytics/ajax/analytics", $data);
		}
		
	}

	/*
	* Ajax Functions
	*/
	public function ajax_add(){

		$address = post("address");
		$location = post("location");
		$limit = (int)post("limit");
		$package = (int)post("package");

		$proxy = $this->model->get("*", $this->tb_proxies, "address = '{$address}'");

		if($address == ""){
			ms(array(
				"status"  => "error",
				"message" => "Address is required"
			));
		}

		if(!empty($proxy)){
			ms(array(
				"status"  => "error",
				"message" => "This proxy already exists"
			));
		}
		
		if(!check_proxy($address)){
			ms(array(
				"status"  => "error",
				"message" => "Proxy is not valid or active"
			));
		}

		$data = array(
			'ids'   => ids(),
			'address'   => $address,
			'location'  => $location,
			'limit'  => $limit,
			'active' => 1,
			'status'  => 1,
			'changed'   => NOW,
			'created'   => NOW
		);

		$this->db->insert($this->tb_proxies, $data);

		ms(array(
			"status" => "success",
			"message" => lang("successfully")
		));

	}

	/****************************************/
	/* CRON                                 */
	/* Time cron: once_per_minute           */
	/****************************************/
	public function cron(){
		$schedule_list = $this->db->select('analytics.*, account.username, account.password, account.proxy, account.default_proxy, account.id as account_id')
		->from($this->tb_analytics." as analytics")
		->join($this->tb_accounts." as account", "analytics.account = account.id")
		->where("account.status = 1 AND analytics.next_action <= '".NOW."'")->limit(10,0)->get()->result();
		
		if(!empty($schedule_list)){
			foreach ($schedule_list as $key => $schedule) {
				if(!permission("instagram/post", $schedule->uid)){
					$this->db->delete($this->tb_posts, array("uid" => $schedule->uid, "time_post >=" => NOW));
				}

				$proxy_data = get_proxy($this->tb_accounts, $schedule->proxy, $schedule);
				try {
					$ig = new InstagramAPI($schedule->username, $schedule->password, $proxy_data->use);
					$result = $ig->analytics->process();
					
					$user_timezone = get_timezone_user(NOW, false, $schedule->uid);
					$user_day = date("Y-m-d", strtotime($user_timezone));

					$check_stats_exist = $this->model->get("id", $this->tb_analytics_stats, " account = '".$schedule->account_id."' AND uid = '".$schedule->uid."' AND date = '".$user_day."'");
					if(empty($check_stats_exist)){

						//Save data
						$user_data = array(
							"media_count" => $result->userinfo->media_count,
							"follower_count" => $result->userinfo->follower_count,
							"following_count" => $result->userinfo->following_count,
							"engagement" => $result->engagement
						);

						
						$data = array(
							"ids" => ids(),
							"uid" => $schedule->uid,
							"account" => $schedule->account_id,
							"data" => json_encode($user_data),
							"date" => date("Y-m-d", strtotime($user_timezone))
						);

						$this->db->insert($this->tb_analytics_stats, $data);

						$save_info = array(
							"engagement" => $result->engagement,
							"average_likes" => $result->average_likes,
							"average_comments" => $result->average_comments,
							"top_hashtags" => $result->top_hashtags,
							"top_mentions" => $result->top_mentions,
							"feeds" => $result->feeds,
							"userinfo" => $result->userinfo,
						);
						
						//Next Action
						$now = date('Y-m-d 00:00:00', strtotime($user_timezone));
						$next_day = date('Y-m-d 00:00:00', strtotime($now) + 86400);
						$data_next_action = array(
							"data" => json_encode($save_info),
							"next_action" => get_timezone_system($next_day, false, $schedule->uid)
						);
						$this->db->update($this->tb_analytics, $data_next_action, "account = '".$schedule->account_id."'");
					}

					echo lang("successfully");
				} catch (Exception $e) {
					echo $e->getMessage();
				}
			}
		}else{
			echo lang("no_activity");
		}
	}
	//****************************************/
	//               END CRON                */
	//****************************************/
}