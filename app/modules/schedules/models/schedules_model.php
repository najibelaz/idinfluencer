<?php
defined('BASEPATH') or exit('No direct script access allowed');

class schedules_model extends MY_Model
{
    private $userid;
    public function __construct()
    {
        parent::__construct();
        $this->userid = user_or_cm();

        $this->tb_accounts = "facebook_accounts";
        $this->tb_posts = "facebook_posts";
        include APPPATH . "../public/facebook/libraries/facebookapi.php";
        include APPPATH . "../public/instagram/libraries/instagramapi.php";
        // include APPPATH."../public/instagram/helpers/analytics_helper.php";
        // include APPPATH."../public/instagram/helpers/instagram_helper.php";
        include APPPATH . "../public/facebook/libraries/facebookcookie.php";
    }
    public static function get_embed_html($shortcode)
    {
        $url = 'https://graph.facebook.com/v9.0/instagram_oembed?url=https://www.instagram.com/p/' . $shortcode . '/&omitscript=false&access_token=EAAFEauXCRm8BAJeAP2dwTPYxmjM8mQABgJBnnPVoRXrZBmHTJ7hraXrOpAq3ZAVQpxOdtzDnYBbBwZA4aP2GggCRMMrUr2sfyUvG00oFINdhQBw2tLIKIuEmqZCt3jZBYsbcaZAvkvp8IaaPgDq4doZA0v0hFiGT7iygWHEUHtfBZCPKGAEXelP9';
        /* Initiate curl */
        $ch = curl_init();
        /* Disable SSL verification */
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        /* Will return the response */
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        /* Set the Url */
        curl_setopt($ch, CURLOPT_URL, $url);
        /* Execute */
        $data = curl_exec($ch);
        /* Close */
        curl_close($ch);
        
        $response = json_decode($data);
        return $response ? $response->thumbnail_url : false;
    }
    /****************************************/
    /*           SCHEDULES POST             */
    /****************************************/
    public function get_calendar_schedules($tb_posts, $type = "queue")
    {
        $id = (int) get("mid");
        $user_timezone = get_field(USERS, $id, "timezone");

        $this->db->select("DATE(CONVERT_TZ(time_post,'" . tz_convert(TIMEZONE) . "','" . tz_convert($user_timezone) . "')) as time_post, COUNT(time_post) as total");
        $this->db->where("uid", $id);

        switch ($type) {
            case 'published':
                $this->db->where("status", 2);
                break;

            case 'unpublished':
                $this->db->where("status", 3);
                break;

            default:
                $this->db->where("status", 1);
                break;
        }

        $this->db->group_by("DATE(CONVERT_TZ(time_post,'" . tz_convert(TIMEZONE) . "','" . tz_convert($user_timezone) . "'))");
        $this->db->order_by('total', 'desc');
        $query = $this->db->get($tb_posts);

        if ($query->result()) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function count_post_on_each_account($username, $tb_posts, $tb_accounts)
    {
        $timezone_first = get_timezone_system(session("schedule_date") . " 00:00:00");
        $timezone_last = get_timezone_system(session("schedule_date") . " 23:59:59");

        $accounts = $this->model->fetch("*, " . $username . " as username", $tb_accounts, "uid = " . $this->userid, "id", "asc");
        foreach ($accounts as $key => $row) {
            $this->db->select("count(*) as count");
            $this->db->where("time_post >=", $timezone_first);
            $this->db->where("time_post <=", $timezone_last);
            $this->db->where("account", $row->id);

            switch ((int) get("t")) {
                case 3:
                    $this->db->where("status", 3);
                    break;

                case 2:
                    $this->db->where("status", 2);
                    break;

                default:
                    $this->db->where("status", 1);
                    break;
            }

            $query = $this->db->get($tb_posts);
            if ($query->row()) {
                $result = $query->row();
                $accounts[$key]->total = $result->count;
            } else {
                $accounts[$key]->total = 0;
            }
        }

        return $accounts;
    }

    public function count_schedules($tb_posts)
    {
        $timezone_first = get_timezone_system(session("schedule_date") . " 00:00:00");
        $timezone_last = get_timezone_system(session("schedule_date") . " 23:59:59");

        $this->db->select('status, COUNT(status) as total');
        $this->db->where("uid", $this->userid);
        $this->db->where("time_post >=", $timezone_first);
        $this->db->where("time_post <=", $timezone_last);

        $this->db->group_by("status");
        $query = $this->db->get($tb_posts);

        $count = array();

        if ($query->result()) {

            $result = $query->result();
            foreach ($result as $key => $value) {
                $count[$value->status] = $value->total;
            }
            return $count;
        } else {
            return false;
        }
    }

    public function get_schedules($page = 0, $username = "username", $tb_posts = "", $tb_account = "")
    {
        $timezone_first = get_timezone_system(session("schedule_date") . " 00:00:00");
        $timezone_last = get_timezone_system(session("schedule_date") . " 23:59:59");

        $type = (int) post("id");
        $ids = segment("4");

        $this->db->select("post.*, account." . $username . " as username");
        $this->db->from($tb_posts . " as post");
        $this->db->join($tb_account . " as account", "account.id = post.account");

        switch ($type) {
            case 3:
                set_session("schedule_type", 3);
                $this->db->where("post.status", 3);
                break;

            case 2:
                set_session("schedule_type", 2);
                $this->db->where("post.status", 2);
                break;

            default:
                set_session("schedule_type", 1);
                $this->db->where("post.status", 1);
                break;
        }

        if ($ids) {
            $this->db->where("account.ids", $ids);
        }
        $this->db->where("time_post >=", $timezone_first);
        $this->db->where("time_post <=", $timezone_last);
        $this->db->where("post.uid", $this->userid);
        $this->db->order_by("time_post", "desc");
        $this->db->limit(1000, (int) $page * 24);
        $query = $this->db->get();

        if ($query->result()) {
            return $query->result();
        } else {
            return false;
        }
    }
    //****************************************/
    //         END SCHEDULES POST            */
    //****************************************/

    public function get_all_schedules($inputs = array())
    {
        if (session("cm_uid")) {
            $uid = session('cm_uid');
        } else {
            $uid = session('uid');
        }
        $data = array();
        if (count($inputs) > 0) {
            $sc_type = isset($inputs['sc_type']) ? $inputs['sc_type'] : '';
            $social_filter = isset($inputs['social_filter']) ? $inputs['social_filter'] : '';
            $date_from = isset($inputs['date_from']) ? $inputs['date_from'] : '';
            $date_to = isset($inputs['date_to']) ? $inputs['date_to'] : '';
        }
        $this->db->select('*');
        $this->db->from('shedules');
        $dateTo = date('Y-') . (date('m') + 1) . '-31';
        $dateFrom = date('Y-m-') . '01';
        if (!empty($date_from)) {
            list($d, $m, $y) = explode('/', (string) $date_from);
            $dateFrom = $y . '-' . $m . '-' . $d;
        }

        if (!empty($date_to)) {
            list($d, $m, $y) = explode('/', (string) $date_to);
            $dateTo = $y . '-' . $m . '-' . $d;
        }
        if ($dateFrom != $dateTo) {
            $this->db->where('shedule_date BETWEEN "' . $dateFrom . '" and "' . $dateTo . '"');
        } else {
            $this->db->where('shedule_date LIKE "%' . $dateFrom . '%" ');
        }
        $this->db->order_by('shedule_date', 'asc');
        $query = $this->db->get();
        $result = $query->result();
        if ($result) {
            foreach ($result as $item) {
                list($year, $mount) = explode('-', $item->shedule_date);
                $post = get_post($item->id_post, $item->type);
                if ($uid == $post->uid) {
                    $get_mount = $this->get_mount($mount);
                    if (!empty($sc_type) || !empty($social_filter)) {
                        if (in_array(ucfirst($post->social_label), $social_filter) && in_array($post->status, $sc_type)) {
                            $data[$get_mount][$item->id] = $item;
                        }
                    } else {
                        $data[$get_mount][$item->id] = $item;
                    }
                }

            }
        }
        return $data;

    }
	public function get_all_schedules_cal1($inputs = array())
    {
        $user = $this->model->get("ids,role", USERS, "id = '" . session('uid') . "'");
        if (session("cm_uid")) {
            $uid = session('cm_uid');
        } else {
            $uid = session('uid');
        }
        $data = array();
        $data_posts = array();
        // $account_filter = array    ();
        if (count($inputs) > 0) {
            $sc_type = isset($inputs['sc_type']) ? $inputs['sc_type'] : '';
            $social_filter = isset($inputs['social_filter']) ? $inputs['social_filter'] : '';
            $date_from = isset($inputs['date_from']) ? $inputs['date_from'] : '';
            $grps_filter = isset($inputs['grps_filter']) ? $inputs['grps_filter'] : '';
            $account_filter = isset($inputs['af']) ? $inputs['af'] : '';
        } 
        $accounts_selected = array();
        if ($account_filter) {
            foreach ($account_filter as $key => $value) {
                list($grp, $acc) = explode("_", $value);
                // if (in_array($grp, $grps_filter)) {
                    $accounts_selected[] = $acc;
                // }
            }
        }else{
            $list_accounts=$this->get_groups1();
            // echo "<pre>";
            // var_dump($list_accounts);die();
            foreach($list_accounts as $list_account){
                $twitters=$list_account["twitter"];
                foreach($twitters as $twitter){
                    if($twitter!=null){
                        // var_dump($twitter[0]);die();
                        $accounts_selected[] = $twitter[0]->id;
                    }
                }
                $instagrams=$list_account["instagram"];
                foreach($instagrams as $instagram){
                    if($instagram!=null){
                        $accounts_selected[] = $instagram[0]->id;
                    }
                }
                $facebooks=$list_account["facebook"];
                foreach($facebooks as $facebook){
                    if($facebook!=null){
                        $accounts_selected[] = $facebook[0]->id;
                    }
                }
            }
        }
        // echo "<pre>";var_dump($accounts_selected);die();
        $directory = APPPATH . '../public/';
        $scanned_directory = array_diff(scandir($directory), array('..', '.'));
        foreach ($scanned_directory as $key => $directory) {
            $this->db->select('posts.*');
            $this->db->from($directory . '_posts posts');
            $this->db->join(USERS . ' user', 'user.id= posts.uid');
            $this->db->join($directory . '_accounts accounts', 'accounts.uid = user.id');
            if ($accounts_selected) {
                // die("here");
                $this->db->where_in('accounts.id', $accounts_selected);
            } else {
                $this->db->where_in('accounts.id', $account_filter);
            }
            if ($user->role == "customer") {
                $this->db->where('posts.uid', session('uid'));

            } elseif ($user->role == "admin") {
                // if(session("cm_uid")){
                //     $this->db->where('posts.uid',session('cm_uid'));
                // }else{
                // $this->db->join('user_group', 'user_group.id_user = user.ids');
                // $this->db->join('customer_cm','customer_cm.id_customer = user.id' );

                // $this->db->where_in('user_group.id_group',$grps_filter);
                // }
            } else {

                // if (!session("cm_uid")) {
                //     $this->db->join('user_group', 'user_group.id_user = user.ids');
                //     $this->db->join($user->role . '_group as groups', 'groups.id_group = user_group.id_group');
                //     $this->db->where('groups.id_user', $user->ids);
                //     // $this->db->where_in('user_group.id_group',$grps_filter);
                // } else {
                //     $this->db->where('posts.uid', session('cm_uid'));
                // }
            }

            if (isset($inputs['date_from'])) {
                if (!empty($inputs['date_from'])) {
                    list($month, $yearr) = explode('/', $inputs['date_from']);
                } else {
                    $month = date('m');
                    $yearr = date('Y');
                }

                $this->db->where('MONTH(time_post) = "' . $month . '"');
                $this->db->where('YEAR(time_post) = "' . $yearr . '"');
            } else {
                $this->db->where('MONTH(time_post) = MONTH(CURRENT_DATE())');
            }
            $this->db->where('(posts.status != 6 or posts.status != 7)');

            $this->db->order_by('time_post', 'asc');

            $query = $this->db->get();
            $result = $query->result();
            // var_dump($result);die();
            if ($result) {
                foreach ($result as $item) {
                    list($year, $time) = explode(' ', $item->time_post);
                    list($year, $mount, $day) = explode('-', $year);
                    $date = date_create($item->time_post);
                    $time_post = date_format($date, "Y-m-d");
                    //if($uid == $post->uid) {
                    // var_dump($sc_type);die();
                    if(in_array("1", $sc_type)){
                        $sc_type[]="5";
                    }
                    if (!empty($sc_type) || !empty($social_filter)) {
                        if (in_array(ucfirst($item->social_label), $social_filter) && in_array($item->status, $sc_type)) {
                            $data[$day][$item->id] = $item;
                            $data_posts[$time_post][] = $item;
                        }
                    } else {
                        $data[$day][$item->id] = $item;
                        $data_posts[$time_post][] = $item;
                    }
                    //}

                }
            }
        }
        // die;
        // dump($data);
        return $data;

    }
    public function get_all_schedules_cal($inputs = array())
    {
        $user = $this->model->get("ids,role", USERS, "id = '" . session('uid') . "'");
        if (session("cm_uid")) {
            $uid = session('cm_uid');
        } else {
            $uid = session('uid');
        }
        $data = array();
        $data_posts = array();
        // $account_filter = array    ();
        if (count($inputs) > 0) {
            $sc_type = isset($inputs['sc_type']) ? $inputs['sc_type'] : '';
            $social_filter = isset($inputs['social_filter']) ? $inputs['social_filter'] : '';
            $date_from = isset($inputs['date_from']) ? $inputs['date_from'] : '';
            $grps_filter = isset($inputs['grps_filter']) ? $inputs['grps_filter'] : '';
            $account_filter = isset($inputs['af']) ? $inputs['af'] : '';
        }
        $accounts_selected = array();
        if ($account_filter) {
            foreach ($account_filter as $key => $value) {
                list($grp, $acc) = explode("_", $value);
                if (in_array($grp, $grps_filter)) {
                    $accounts_selected[] = $acc;
                }
            }
        }
        $directory = APPPATH . '../public/';
        $scanned_directory = array_diff(scandir($directory), array('..', '.'));
        foreach ($scanned_directory as $key => $directory) {
            $this->db->select('posts.*');
            $this->db->from($directory . '_posts posts');
            $this->db->join(USERS . ' user', 'user.id= posts.uid');
            $this->db->join($directory . '_accounts accounts', 'accounts.uid = user.id');
            if ($accounts_selected) {
                $this->db->where_in('accounts.ids', $accounts_selected);
            } else {
                $this->db->where_in('accounts.ids', $account_filter);
            }
            $this->db->where('posts.status != 6');
            $this->db->where('posts.status != ' . ST_DELETED);
            if ($user->role == "customer") {
                $this->db->where('posts.uid', session('uid'));

            } elseif ($user->role == "admin") {
                // if(session("cm_uid")){
                //     $this->db->where('posts.uid',session('cm_uid'));
                // }else{
                $this->db->join('user_group', 'user_group.id_user = user.ids');
                // $this->db->where_in('user_group.id_group',$grps_filter);
                // }
            } else {
                if (!session("cm_uid")) {
                    $this->db->join('user_group', 'user_group.id_user = user.ids');
                    $this->db->join($user->role . '_group as groups', 'groups.id_group = user_group.id_group');
                    $this->db->where('groups.id_user', $user->ids);
                    // $this->db->where_in('user_group.id_group',$grps_filter);
                } else {
                    $this->db->where('posts.uid', session('cm_uid'));
                }
            }

            if (isset($inputs['date_from'])) {
                if (!empty($inputs['date_from'])) {
                    list($month, $yearr) = explode('/', $inputs['date_from']);
                } else {
                    $month = date('m');
                    $yearr = date('Y');
                }

                $this->db->where('MONTH(time_post) = "' . $month . '"');
                $this->db->where('YEAR(time_post) = "' . $yearr . '"');
            } else {
                $this->db->where('MONTH(time_post) = MONTH(CURRENT_DATE())');
            }
            $this->db->order_by('time_post', 'asc');

            $query = $this->db->get();
            $result = $query->result();
            if ($result) {
                foreach ($result as $item) {
                    list($year, $time) = explode(' ', $item->time_post);
                    list($year, $mount, $day) = explode('-', $year);
                    $date = date_create($item->time_post);
                    $time_post = date_format($date, "Y-m-d");
                    //if($uid == $post->uid) {
                    if (!empty($sc_type) || !empty($social_filter)) {
                        if (in_array(ucfirst($item->social_label), $social_filter) && in_array($item->status, $sc_type)) {
                            $data[$day][$item->id] = $item;
                            $data_posts[$time_post][] = $item;
                        }
                    } else {
                        $data[$day][$item->id] = $item;
                        $data_posts[$time_post][] = $item;
                    }
                    //}

                }
            }
        }
        // die;
        // dump($data);
        return $data;

    }
    public function get_all_schedules_cal_list($inputs = array())
    {
        $user = $this->model->get("ids,role", USERS, "id = '" . session('uid') . "'");
        if (session("cm_uid")) {
            $uid = session('cm_uid');
        } else {
            $uid = session('uid');
        }
        $data = array();
        $data_posts = array();
        // $account_filter = array    ();
        if (count($inputs) > 0) {
            $sc_type = isset($inputs['sc_type']) ? $inputs['sc_type'] : '';
            $social_filter = isset($inputs['social_filter']) ? $inputs['social_filter'] : '';
            $date_from = isset($inputs['date_from']) ? $inputs['date_from'] : '';
            $grps_filter = isset($inputs['grps_filter']) ? $inputs['grps_filter'] : '';
            $account_filter = isset($inputs['af']) ? $inputs['af'] : '';
        }
        $directory = APPPATH . '../public/';
        $scanned_directory = array_diff(scandir($directory), array('..', '.'));
        foreach ($scanned_directory as $key => $directory) {
            $this->db->select('posts.*');
            $this->db->from($directory . '_posts posts');
            $this->db->join(USERS . ' user', 'user.id= posts.uid');
            $this->db->join($directory . '_accounts accounts', 'accounts.uid = user.id');
            $this->db->where_in('accounts.ids', $account_filter);
            $this->db->where('posts.status != 6');
            $this->db->where('posts.status != ' . ST_DELETED);
            if ($user->role == "customer") {
                $this->db->where('posts.uid', session('uid'));

            } elseif ($user->role == "admin") {
                // if(session("cm_uid")){
                //     $this->db->where('posts.uid',session('cm_uid'));
                // }else{
                $this->db->join('user_group', 'user_group.id_user = user.ids');
                // $this->db->where_in('user_group.id_group',$grps_filter);
                // }
            } else {
                if (!session("cm_uid")) {
                    $this->db->join('user_group', 'user_group.id_user = user.ids');
                    $this->db->join($user->role . '_group as groups', 'groups.id_group = user_group.id_group');
                    $this->db->where('groups.id_user', $user->ids);
                    // $this->db->where_in('user_group.id_group',$grps_filter);
                } else {
                    $this->db->where('posts.uid', session('cm_uid'));
                }
            }

            if (isset($inputs['date_from'])) {
                if (!empty($inputs['date_from'])) {
                    list($month, $yearr) = explode('/', $inputs['date_from']);
                } else {
                    $month = date('m');
                    $yearr = date('Y');
                }

                $this->db->where('MONTH(time_post) = "' . $month . '"');
                $this->db->where('YEAR(time_post) = "' . $yearr . '"');
            } else {
                $this->db->where('MONTH(time_post) = MONTH(CURRENT_DATE())');
            }
            $this->db->order_by('time_post', 'asc');

            $query = $this->db->get();
            $result = $query->result();
            if ($result) {
                foreach ($result as $item) {
                    list($year, $time) = explode(' ', $item->time_post);
                    list($year, $mount, $day) = explode('-', $year);
                    $date = date_create($item->time_post);
                    $time_post = date_format($date, "Y-m-d");
                    //if($uid == $post->uid) {
                    if (!empty($sc_type) || !empty($social_filter)) {
                        if (in_array(ucfirst($item->social_label), $social_filter) && in_array($item->status, $sc_type)) {
                            $data[$item->id] = $item;
                            $data_posts[$time_post][] = $item;
                        }
                    } else {
                        $data[$item->id] = $item;
                        $data_posts[$time_post][] = $item;
                    }
                    //}

                }
            }
        }
        // die;
        return $data;

    }
    public function get_post($id_post = 0, $type = '')
    {
        $this->db->select('*');
        $table = '';
        if ($type == 'facebook') {
            $table = 'facebook_posts';
        } elseif ($type == 'twitter') {
            $table = 'twitter_posts';
        } elseif ($type == 'instagram') {
            $table = 'instagram_posts';
        }
        $this->db->from($table);
        $this->db->where('id BETWEEN "' . $dateFrom . '" and "' . $dateTo . '"');

        echo $id_post;exit;
    }
    public function get_groups()
    {
        $user = $this->model->get("ids,role", USERS, "id = '" . session('uid') . "'");
        $client = array();
        if (session("cm_uid")) {
            $client = $this->model->get("ids,role", USERS, "id = '" . session('cm_uid') . "'");
        }
        $this->db->select('grp.*');
        $this->db->from('general_groups grp');
        if ($user->role == "customer") {
            $this->db->join('user_group as groups', 'groups.id_group = grp.ids');
            $this->db->where('groups.id_user', $user->ids);
        } elseif ($user->role != "admin") {
            if (!session("cm_uid")) {
                $this->db->join($user->role . '_group as groups', 'groups.id_group = grp.ids');
                $this->db->where('groups.id_user', $user->ids);
            } else {
                $this->db->join('user_group as groups', 'groups.id_group = grp.ids');
                $this->db->where('groups.id_user', $client->ids);
            }
        }
        return $this->db->get()->result();
    }
	public function get_groups1()
    {
        $user = $this->model->get("id,ids,role", USERS, "id = '" . session('uid') . "'");

		$this->db->select("g2.id,g2.ids,g2.fullname,g1.id as 'id_user'");
        $this->db->from('general_users as g1');
		$this->db->join('customer_cm','customer_cm.id_customer = g1.id' );
		$this->db->join('general_users as g2','customer_cm.id_manager = g2.id' );
        if ($user->role == "customer") {
			$this->db->where('customer_cm.id_customer', $user->id);
		}elseif($user->role=="manager"){
			$this->db->where('customer_cm.id_manager', $user->id);
		}elseif($user->role=="responsable"){
			$this->db->join('responsable_cm','responsable_cm.id_manager = customer_cm.id_manager' );
			$this->db->where('responsable_cm.id_responsable', $user->id);
		}
		$users=$this->db->get()->result();
		$list_account=array();
		foreach($users as $user){
			$this->db->select('accounts.* , "facebook" as social_label');
			$this->db->from('facebook_accounts accounts');
			$this->db->where('accounts.uid', $user->id_user);
			$facebook=$this->db->get()->result();

			$this->db->select('accounts.* , "instagram" as social_label');
			$this->db->from('instagram_accounts accounts');
			$this->db->where('accounts.uid', $user->id_user);
			$instagram=$this->db->get()->result();

			$this->db->select('accounts.* , "twitter" as social_label');
			$this->db->from('twitter_accounts accounts');
			$this->db->where('accounts.uid', $user->id_user);
			$twitter=$this->db->get()->result();   

			if(array_search($user->id,array_column($list_account, 'id_manager')) === false){

				$list_account[]=array(
					"id_manager"=>$user->id, 
					"fullname"=>$user->fullname, 
					"id_user"=>$user->id_user, 
					"facebook"=>[$facebook],
					"instagram"=>[$instagram],
					"twitter"=>[$twitter],
				);
			}else{
				if($facebook!=null)
				array_push($list_account[array_search($user->id,array_column($list_account, 'id_manager'))]["facebook"],$facebook);
				if($twitter!=null)
				array_push($list_account[array_search($user->id,array_column($list_account, 'id_manager'))]["twitter"],$twitter);
				if($instagram!=null)
				array_push($list_account[array_search($user->id,array_column($list_account, 'id_manager'))]["instagram"],$instagram);
			}
		}
		// echo "<pre>";var_dump($list_account);die();
        return $list_account;
    }

    public function get_mount($mount)
    {
        switch ($mount) {
            case '01':
                return 'Janvier';
                break;
            case '02':
                return 'Février';
                break;
            case '03':
                return 'Mars';
                break;
            case '04':
                return 'Avril';
                break;
            case '05':
                return 'Mai';
                break;
            case '06':
                return 'Juin';
                break;
            case '07':
                return 'Juillet';
                break;
            case '08':
                return 'Aout';
                break;
            case '09':
                return 'Septembre';
                break;
            case '10':
                return 'Octobre';
                break;
            case '11':
                return 'Novembre';
                break;
            case '12':
                return 'Décembre';
                break;
            default:
                return $mount;
                break;
        }
    }
}
