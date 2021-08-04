<?php
defined('BASEPATH') or exit('No direct script access allowed');

class facebook extends MX_Controller
{
    public $table;
    public $module;
    public $module_name;
    public $module_icon;

    public function __construct()
    {
        parent::__construct();

        $this->table = FACEBOOK_ACCOUNTS;
        $this->tb_accounts = FACEBOOK_ACCOUNTS;
        $this->tb_analytics = "facebook_analytics";
        $this->tb_analytics_stats = "facebook_analytics_stats";
        $this->module = get_class($this);
        $this->module_name = lang("facebook_accounts");
        $this->module_icon = "fa fa-facebook-square";
        $this->load->model($this->module . '_model', 'model');
        $this->load->model('analytics_model', 'analytics');
        if (is_customer()) {
            $this->accounts = $this->model->fetch("*", FACEBOOK_ACCOUNTS, "uid = '" . session("uid") . "'");
            $this->accounts_insta = $this->model->fetch("*", 'instagram_accounts', "uid = '" . session("uid") . "'");
        } else if(is_admin()) {
            $this->accounts = $this->model->fetch(FACEBOOK_ACCOUNTS . ".*", array(FACEBOOK_ACCOUNTS, USERS), FACEBOOK_ACCOUNTS . '.uid = ' . USERS . '.id and ' . USERS . '.status=1 and role="customer"');
            $this->accounts_insta = $this->model->fetch("instagram_accounts.*", array('instagram_accounts', USERS), 'instagram_accounts.uid = ' . USERS . '.id and ' . USERS . '.status=1  and role="customer"');
        }else{
            $users = getUsersByRole($from='acceuil');
            $list_user="(";
            foreach ($users as $user){
                    $list_user.="'".$user->ids."',";
            }
            $list_user=rtrim($list_user, ",");
            // var_dump($list_user);die();
            $list_user.=")";
            
            $this->accounts = $this->model->fetch(FACEBOOK_ACCOUNTS . ".*", array(FACEBOOK_ACCOUNTS, USERS), FACEBOOK_ACCOUNTS . '.uid = ' . USERS . '.id and ' . USERS . '.status=1 and role="customer" and '.USERS.'.ids in '.$list_user);
            // var_dump($this->accounts);die();
            $this->accounts_insta = $this->model->fetch("instagram_accounts.*", array('instagram_accounts', USERS), 'instagram_accounts.uid = ' . USERS . '.id and ' . USERS . '.status=1  and role="customer" and '.USERS.'.ids in '.$list_user);
        }
    }
    public function stats($ids = '', $template = "")
    {
        $account = $this->model->get("*", $this->tb_accounts, "ids = '" . $ids . "'");

        if (empty($account)) {
            $view = $this->load->view("analytics/ajax/empty", array(), true);
            return $this->template->build('analytics/index', array("view" => $view, "accounts" => $this->accounts, "accounts_insta" => $this->accounts_insta));
        }
        $user_account = $this->model->get("*", USERS, "id = '" . $account->uid . "'");
        // Check Start First Time
        $action_exist = $this->model->get("*", $this->tb_analytics, "account = '" . $account->id . "'");
        $fb = new FacebookAPI();
        $token = $account->access_token;
        $fb->set_access_token($token);
        $fbId = $account->pid;
        if ($template == 'analytics') {
            try {
                
                $url = '/' . $fbId . '?fields=access_token';
                $token_insights = $fb->get($url);
                // var_dump($fbId,$token_insights) ;die();
                $token = $token_insights->access_token;
                $fb2 = new FacebookAPI();
                $fb2->set_access_token($token);
                $range_date="";
                if(get("date_from")!=""){
                    
                    $date_from = strtotime(str_replace('/', '-', get("date_from") ));
			        $newDate = date("Y-m-d", $date_from);
                    $range_date="?since=".strtotime($newDate);
                }
                if(get("date_to")!=""){
                    $date_to = strtotime(str_replace('/', '-', get("date_to") ));
			        $newDate = date("Y-m-d", $date_to);
                    $range_date.="&until=".strtotime($newDate);
                }
                if(get("date_from")==Null && get("date_to")==Null){
                    $date_from = strtotime(str_replace('/', '-',"01/". date("m/Y") ));
			        $newDate = date("Y-m-d", $date_from);
                    $range_date.="?since=".strtotime($newDate);
                    
                    $date_to = strtotime(str_replace('/', '-',date("d/m/Y") ));
			        $newDate = date("Y-m-d", $date_to);
                    $range_date.="&until=".strtotime($newDate);
                    // var_dump(get("date_to"),get("date_from"),$range_date);die();
                }

                $profile = $fb2->get('/' . $fbId . "/feed?fields=admin_creator,full_picture,id,message");
                // echo"<pre>";var_dump($profile);die();
				$data_insights_page_fans = $fb2->get('/' . $fbId . "/insights/page_fan_adds".$range_date."&access_token=" . $token)->data;
                $data_insights_page_impressions = $fb2->get('/' . $fbId . "/insights/page_impressions".$range_date."&access_token=" . $token)->data;
                $data_insights_page_engaged_users = $fb2->get('/' . $fbId . "/insights/page_engaged_users".$range_date."&access_token=" . $token)->data;
                $count_page_impressions = 0;
                $count_page_engaged_users = 0;
                $count_page_fans = 0;
                $table_page_fans_data="";
                $table_page_fans_date="";
                $table_page_engagement_data="";
                $table_page_engagement_date="";
				if ($data_insights_page_fans != null) {
                    foreach ($data_insights_page_fans[0] as $key => $page_fans) {
                        if ($key == "values") {
                            foreach ($page_fans as  $key_value => $value) {
                                $count_page_fans += $value->value;
                                $table_page_fans_data .= "'{$value->value}',";
                                $date = date("d-m-Y", strtotime($value->end_time));
                                $table_page_fans_date .= "'{$date}',";
                            }
                        }
                    }
				}
                if ($data_insights_page_impressions != null) {
                    foreach ($data_insights_page_impressions as $key => $page_impression) {
                        if ($key == "values") {   
                            foreach ($page_impression->values as $value) {
                                $count_page_impressions += $value->value;
                            }
                        }
                    }
                }
                if ($data_insights_page_engaged_users != null) {
                    
                    foreach ($data_insights_page_engaged_users as $key => $page_impression) {
                        if ($page_impression->title == "Daily Page Engaged Users") {
                            foreach ($page_impression->values as $value) {
                                $count_page_engaged_users += $value->value;
                                $table_page_engagement_data .= "'{$value->value}',";
                                $date = date("d-m-Y", strtotime($value->end_time));
                                $table_page_engagement_date .= "'{$date}',";
                            }
                        }
                    }
                }
                $userinfo = $fb->get(
                    '/' . $fbId . '?fields=id,name,fan_count,description,website,picture,url'
                );
                $average = $fb->get(
                    '/' . $fbId . '?fields=new_like_count,fan_count,rating_count,posts{is_published,created_time,likes.summary(true),comments.summary(true)}'
                );
                // dump($userinfo);
                $count_comments = 0;
                $count_likes = 0;
                foreach ($average->posts->data as $key => $post) {
                    $count_comments = $count_comments + $post->comments->summary->total_count;
                    $count_likes = $count_likes + $post->likes->summary->total_count;
                }
                $count_posts = 0;
                foreach ($average->posts->data as $key => $post) {
                    $count_posts = $count_posts + 1;
                }
                $average_comments = 0;
                $average_likes = 0;
                if ($count_comments != 0 && $count_posts != 0) {
                    $average_comments = number_format($count_comments / $count_posts, 2);
                }

                if ($count_likes != 0 && $count_posts != 0) {
                    $average_likes = number_format($count_likes / $count_posts, 2);
                }
                $account_data = array(
                    'average_comments' => $average_comments,
                    'average_likes' => $average_likes,
                );
                $user_timezone = get_timezone_user(NOW);
                $user_day = date("Y-m-d", strtotime($user_timezone));

                // $check_stats_exist = $this->model->get("id", $this->tb_analytics_stats, " account = '".$account->id."' AND  date = '".$user_day."'");
                $check_stats_exist = $this->model->get("id", $this->tb_analytics_stats, " account = '" . $account->id . "' AND uid = '" . session("uid") . "' AND date = '" . $user_day . "'");
                if (empty($check_stats_exist)) {

                    //Save data
                    $user_data = array(
                        "media_count" => $count_posts,
                        "followers_count" => $userinfo->fan_count,
                    );

                    $userinfo = (array) $userinfo;

                    $userinfo['media_count'] = $count_posts;

                    $userinfo = (array) $userinfo;

                    $data = array(
                        "ids" => ids(),
                        "uid" => session("uid"),
                        "account" => $account->id,
                        "data" => json_encode($user_data),
                        "date" => date("Y-m-d", strtotime($user_timezone)),
                    );

                    $this->db->insert($this->tb_analytics_stats, $data);

                    $save_info = array(
                        "average_likes" => $average_likes,
                        "average_comments" => $average_comments,
                        "userinfo" => $userinfo,
                    );

                    //Next Action
                    $now = date('Y-m-d 00:00:00', strtotime($user_timezone));
                    $next_day = date('Y-m-d 00:00:00', strtotime($now) + 86400);
                    $data_next_action = array(
                        "ids" => ids(),
                        "uid" => $account->uid,
                        "account" => $account->id,
                        "data" => json_encode($save_info),
                        "next_action" => get_timezone_system($next_day),
                    );

                    $this->db->insert($this->tb_analytics, $data_next_action);
                }
                $result['userinfo'] = $userinfo;
                $result['account_data'] = $account_data;
                // $data = array(
                //     "result" => $result,
                //     "account" => $account
                // );
                $table_page_fans_data  = "[".substr($table_page_fans_data, 0, -1)."]";
                $table_page_fans_date  = "[".substr($table_page_fans_date, 0, -1)."]";
                $table_page_engagement_data  = "[".substr($table_page_engagement_data, 0, -1)."]";
                $table_page_engagement_date  = "[".substr($table_page_engagement_date, 0, -1)."]";
                $data = array(
                    "result" => $this->analytics->get_stats($ids),
                    "account" => $account,

                    "count_page_impressions" => $count_page_impressions,
					"count_page_engaged_users" => $count_page_engaged_users,
                    "count_page_fans" => $count_page_fans,
                    "table_page_fans_data" => $table_page_fans_data,
                    "table_page_fans_date" => $table_page_fans_date,
                    "table_page_engagement_data" => $table_page_engagement_data,
                    "table_page_engagement_date" => $table_page_engagement_date
                );
                // echo "<pre>";var_dump($data);die();
                $data['ids'] = $ids;
            } catch (Exception $e) {

            }

        } else {
            $template = 'mail';

            $mois = (int) date('m');
            $annee = (int) date('Y');
            if ($mois == 1) {
                $annee = $annee - 1;
            }
            $dateFrom = $annee . "-" . ($mois - 2) . "-01";
            $dateTo = $annee . "-" . ($mois - 1) . "-31";

            // $pages = $fb->get_search('107427350901897','post_likers');
            try {
                // Returns a `FacebookFacebookResponse` object
                $data = $fb->get(
                    '/' . $fbId . '?fields=id,name,new_like_count,fan_count,rating_count,posts.until(' . $dateTo . ').since(' . $dateFrom . '){is_published,created_time,comments}'
                );
                $count_comments_recus = 0;
                $count_comments_publie = 0;
                foreach ($data->posts->data as $key => $post) {
                    if (isset($post->comments)) {
                        foreach ($post->comments->data as $key => $comment) {
                            if ($comment->from->id == $fbId) {
                                $count_comments_publie = $count_comments_publie + 1;
                            } else {
                                $count_comments_recus = $count_comments_recus + 1;
                            }
                        }
                    }
                }
                $count_posts_recus = 0;
                $count_posts_publie = 0;
                foreach ($data->posts->data as $key => $post) {

                    $id = explode('_', $post->id)[0];
                    if ($id == $fbId) {
                        $count_posts_publie = $count_posts_publie + 1;
                    } else {
                        $count_posts_recus = $count_posts_recus + 1;
                    }
                }
                $data = (array) $data;
                $data['count_posts_recus'] = $count_posts_recus;
                $data['count_posts_publie'] = $count_posts_publie;
                $data['count_comments_recus'] = $count_comments_recus;
                $data['count_comments_publie'] = $count_comments_publie;
                $data['ids'] = $ids;
                $data['user_account'] = $user_account;

            } catch (FacebookExceptionsFacebookResponseException $e) {
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            } catch (FacebookExceptionsFacebookSDKException $e) {
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }

        }
        // echo "<pre>";var_dump($data);die();
        if (!$this->input->is_ajax_request()) {
            $view = $this->load->view("analytics/ajax/" . $template, $data, true);
            $this->template->build('analytics/index', array("view" => $view, "accounts" => $this->accounts, "accounts_insta" => $this->accounts_insta));
        } else {
            $this->load->view("analytics/ajax/" . $template, $data);
        }

    }
    public function block_general_settings()
    {
        $data = array();
        $this->load->view('account/general_settings', $data);
    }

    public function block_list_account()
    {
        $uid = user_or_cm();
        $data = array(
            'module' => $this->module,
            'module_name' => $this->module_name,
            'module_icon' => $this->module_icon,
            'list_account' => $this->model->fetch("id, fullname, avatar, ids, status, pid, type", $this->table, "uid = '" . $uid . "'"),
        );

        $this->load->view("account/index", $data);
    }

    public function oauth()
    {
        $fb = new FacebookAPI(FACEBOOK_APP_ID, FACEBOOK_APP_SECRET);
        redirect($fb->login_url());
    }

    public function popup_add_account()
    {
        $this->load->library('user_agent');
        $data = array(
            "user_agent" => $this->agent->browser(),
        );
        $this->load->view('account/popup_add_account', $data);
    }

    public function ajax_get_access()
    {
        $ids = ids();
        $access_token = $this->input->post("access_token");

        if (!permission("facebook_enable")) {
            ms(array(
                "status" => "error",
                "message" => lang("disable_feature"),
            ));
        }

        if ($access_token == "") {
            ms(array(
                "status" => "error",
                "message" => lang('access_token_is_required'),
            ));
        }

        $generate_access_token = json_decode($access_token);

        if (is_object($generate_access_token)) {
            if (isset($generate_access_token->access_token)) {

                $access_token = $generate_access_token->access_token;

            } else if (isset($generate_access_token->error_msg)) {

                ms(array(
                    "status" => "error",
                    "message" => $generate_access_token->error_msg,
                ));

            } else {

                ms(array(
                    "status" => "error",
                    "message" => lang('can_not_generate_token_please_try_again'),
                ));

            }
        }

        if (strrpos($access_token, "#") == true && strrpos($access_token, "&")) {
            $link_token = explode("#", $access_token);
            if (count($link_token) == 2) {
                parse_str($link_token[1], $param_token);
                if (is_array($param_token) && isset($param_token['access_token'])) {
                    $access_token = $param_token['access_token'];
                }
            }
        }

        $fb = new FacebookAPI();
        $fb->set_access_token($access_token);
        $user = $fb->get_current_user();

        if (is_string($user)) {
            ms(array(
                "status" => "error",
                "message" => $user,
            ));
        }

        set_session("facebook_access_token", $access_token);

        ms(array(
            "status" => "success",
            "message" => lang("successfully"),
        ));
    }

    public function ajax_get_cookie()
    {

        $fbUserId = post('fb_cookie_user');
        $fbSess = post('fb_cookie_xs');
        $proxy = post('proxy');

        try {
            $fb_cookie = new FacebookCookie($fbUserId, $fbSess, $proxy);
            $myInfo = $fb_cookie->myInfo();

            set_session("fb_cookie_user", $fbUserId);
            set_session("fb_cookie_xs", $fbSess);

            ms(array(
                "status" => "success",
                "message" => "",
            ));

        } catch (Exception $e) {
            ms(array(
                "status" => "error",
                "message" => $e->getMessage(),
            ));
        }
    }

    public function add_account()
    {
        $access_token = session("facebook_access_token");
        $fbUserId = session("fb_cookie_user");
        $fbSess = session("fb_cookie_xs");
        $login_type = 1;
        $userinfo = array();
        
        $fb = new FacebookAPI(FACEBOOK_APP_ID, FACEBOOK_APP_SECRET);
        if (get("code")) {
            $access_token = $fb->get_access_token();
            $login_type = 1;
            set_session("facebook_login_type", 1);
            set_session("facebook_access_token", $access_token);
            
            $fb->set_access_token($access_token);
            $userinfo = $fb->get_current_user();
            $groups = $fb->get_groups("group");
            $pages = $fb->get_groups("page");
            // echo "<pre>";var_dump($pages);die();

        }

        if ($access_token) {

            if (!get("code")) {
                $login_type = 2;
                set_session("facebook_login_type", 2);
            }

            $fb->set_access_token($access_token);
            $userinfo = $fb->get_current_user();
            $groups = $fb->get_groups("group");
            $pages = $fb->get_groups("page");
        }

        if ($fbUserId) {
            if (empty($fbUserId)) {
                redirect(cn("account_manager"));
            }

            $fb = new FacebookCookie($fbUserId, $fbSess);
            $userinfo = (object) $fb->myInfo();
            
            $groups = $fb->getGroups();
            $pages = $fb->getMyPages();

            $pages = (object) array(
                "data" => json_decode(json_encode($pages)),
            );

            $groups = (object) array(
                "data" => json_decode(json_encode($groups)),
            );

            $login_type = 3;
            set_session("facebook_login_type", 3);
        }

        if (empty($userinfo) || is_string($userinfo)) {
            redirect(cn('account_manager?error=' . $userinfo));
        }

        $data = array(
            'module' => $this->module,
            'module_name' => $this->module_name,
            'module_icon' => $this->module_icon,
            'userinfo' => $userinfo,
            'pages' => $pages,
            'groups' => $groups,
            'login_type' => $login_type,
        );

        $this->template->build('account/add_account', $data);
    }

    public function ajax_add_account()
    {
        $accounts = $this->input->post("accounts");
        $access_token = session("facebook_access_token");
        $login_type = (int) session("facebook_login_type");
        $fbUserId = session("fb_cookie_user");
        $fbSess = session("fb_cookie_xs");

        if (empty($accounts)) {
            ms(array(
                "status" => "error",
                "message" => lang('please_select_at_least_one_item'),
            ));
        }

        if ($access_token) {
            $fb = new FacebookAPI();
            $fb->set_access_token($access_token);
            $userinfo = $fb->get_current_user();
            $groups = $fb->get_groups("group");
            $pages = $fb->get_groups("page");
        }

        if ($fbUserId) {
            if (empty($fbUserId)) {
                redirect(cn("account_manager"));
            }

            $access_token = $fbSess;

            $fb = new FacebookCookie($fbUserId, $fbSess);
            $userinfo = (object) $fb->myInfo();
            $groups = $fb->getGroups();
            $pages = $fb->getMyPages();

            $pages = (object) array(
                "data" => json_decode(json_encode($pages)),
            );

            $groups = (object) array(
                "data" => json_decode(json_encode($groups)),
            );
        }

        if (!empty($accounts)) {
            if (!empty($userinfo)) {
                $data = array();
                if (in_array("profile-" . $userinfo->id, $accounts)) {
                    $data = array(
                        "ids" => ids(),
                        "uid" => user_or_cm(),
                        "pid" => $userinfo->id,
                        "type" => "profile",
                        "fullname" => $userinfo->name,
                        "url" => "https://fb.com/" . $userinfo->id,
                        "official" => ($login_type == 1) ? 3 : 1,
                        "login_type" => $login_type,
                        "avatar" => "https://graph.facebook.com/{$userinfo->id}/picture",
                        "access_token" => $access_token,
                        "status" => 1,
                        "changed" => NOW,
                    );

                    $fb_account = $this->model->get("id", $this->table, "pid = '" . $userinfo->id . "' AND uid = '" . user_or_cm() . "'");
                    if (empty($fb_account)) {
                        $data['created'] = NOW;

                        if (!check_number_account($this->table)) {
                            ms(array(
                                "status" => "error",
                                "message" => lang("limit_social_accounts"),
                            ));
                        }

                        $this->db->insert($this->table, $data);
                    } else {
                        $this->db->update($this->table, $data, "id = '{$fb_account->id}'");
                    }
                }
            }

            if (!empty($groups->data)) {
                foreach ($groups->data as $key => $group) {
                    $data = array();
                    if (in_array("group-" . $group->id, $accounts)) {
                        $data = array(
                            "ids" => ids(),
                            "uid" => user_or_cm(),
                            "pid" => $group->id,
                            "type" => "group",
                            "fullname" => $group->name,
                            "url" => "https://fb.com/" . $group->id,
                            "official" => 1,
                            "login_type" => $login_type,
                            "avatar" => "https://ui-avatars.com/api/?name=" . urlencode($group->name) . "&amp;background=dceeee&amp;color=0abb87&amp;font-size=0.5",
                            "access_token" => $access_token,
                            "status" => 1,
                            "changed" => NOW,
                        );

                        $fb_account = $this->model->get("id", $this->table, "pid = '" . $group->id . "' AND uid = '" . user_or_cm() . "'");
                        if (empty($fb_account)) {
                            $data['created'] = NOW;

                            if (!check_number_account($this->table)) {
                                ms(array(
                                    "status" => "error",
                                    "message" => lang("limit_social_accounts"),
                                ));
                            }

                            $this->db->insert($this->table, $data);
                        } else {
                            $this->db->update($this->table, $data, "id = '{$fb_account->id}'");
                        }
                    }

                }
            }

            if (!empty($pages->data)) {
                foreach ($pages->data as $key => $page) {
                    $data = array();

                    $avatar = file_get_contents("https://graph.facebook.com/v4.0/" . $page->id . "/picture?redirect=0");
                    $avatar = json_decode($avatar);

                    if (in_array("page-" . $page->id, $accounts)) {
                        $data = array(
                            "ids" => ids(),
                            "uid" => user_or_cm(),
                            "pid" => $page->id,
                            "type" => "page",
                            "fullname" => $page->name,
                            "url" => "http://facebook.com/" . $page->id,
                            "official" => 1,
                            "login_type" => $login_type,
                            "avatar" => $avatar->data->url,
                            "access_token" => $access_token,
                            "status" => 1,
                            "changed" => NOW,
                        );

                        $fb_account = $this->model->get("id", $this->table, "pid = '" . $page->id . "' AND uid = '" . user_or_cm() . "'");
                        if (empty($fb_account)) {
                            $data['created'] = NOW;

                            if (!check_number_account($this->table)) {
                                ms(array(
                                    "status" => "error",
                                    "message" => lang("limit_social_accounts"),
                                ));
                            }

                            $this->db->insert($this->table, $data);
                        } else {
                            $this->db->update($this->table, $data, "id = '{$fb_account->id}'");
                        }
                    }
                }
            }
        }

        ms(array(
            "status" => "success",
            "message" => lang("successfully"),
        ));
    }

    public function ajax_get_accounts()
    {
        $fb_accounts = $this->model->fetch("*", FACEBOOK_ACCOUNTS, "uid = '" . user_or_cm() . "' AND status = 1");
        $this->load->view("account/ajax_get_accounts", array("accounts" => $fb_accounts));
    }

    public function ajax_get_access_token()
    {
        $username = post("username");
        $password = post("password");
        $proxy = post("proxy");
        $app = post("app");

        if ($username == "") {
            ms(array(
                "status" => "error",
                "message" => lang('facebook_username_is_required'),
            ));
        }

        if ($password == "") {
            ms(array(
                "status" => "error",
                "message" => lang('facebook_password_is_required'),
            ));
        }

        if (!$app) {
            ms(array(
                "status" => "error",
                "message" => lang('facebook_application_is_required'),
            ));
        }

        $fb = new FacebookAPI();

        //Get Link Create Token
        $page = $fb->get_page_access_token($username, encrypt_encode($password), $app);

        ms(array(
            "status" => "success",
            "callback" => '<script>$(".iframe_access_token").html("<iframe src=\'' . $page . '\'></iframe>");</script>',
        ));
    }

    public function ajax_delete_item()
    {
        $this->model->delete($this->table, post("id"), false);
    }
    
}