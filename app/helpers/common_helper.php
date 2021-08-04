<?php


if(!function_exists('post_get')){
	function post_get($name = ""){
		$CI = &get_instance();
		if($name != ""){
			return $CI->input->post_get(trim($name));
		}else{
			return $CI->input->post_get();
		}
	}
}

if(!function_exists('post')){
	function post($name = ""){
		$CI = &get_instance();

		if($name != ""){
			$post = $CI->input->post(trim($name));
			if(is_string($post)){
				return $CI->input->post(trim($name), FALSE);
			}else{
				return $post;
			}
		}else{
			return $CI->input->post();
		}
	}
}

if(!function_exists('get')){
	function get($name = ""){
		$CI = &get_instance();
		return $CI->input->get(trim($name));
	}
}

if(!function_exists('get_role_name')){
	function get_role_name($role=''){
		switch ($role) {
			case 'customer':
				return lang('customer');
				break;
			case 'admin':
				return lang('admin');
				break;
			case 'manager':
				return lang('community_manager');
				break;
			case 'responsable':
				return lang('responsable_CM');
				break;
			
			default:
				# code...
				break;
		}
	}
}
if(!function_exists('jourdansmois')){
	function jourdansmois($mois , $an){
     $enmois = $an*12 + $mois; 
      if (($enmois > 2037 * 12 -1) || ($enmois<1970)){return 0;} 
      $an_suivant = floor(($enmois+1)/12); 
      $mois_suivant = $enmois + 1 - 12 * $an_suivant; 
      $duree=mktime(0, 0, 1, $mois_suivant, 1, $an_suivant)-mktime(0, 0, 1,$mois, 1, $an);
      return ($duree/(3600*24)); 
    }
}

if(!function_exists('get_teamleader_token')){
	function get_teamleader_token($redirectUri=''){
		$accessTokenDefault = ACCESSTOKENDEFAULT;
     	$clientId = CLIENID;
		$clientSecret = CLIENSECRECT;

		$pos = strrpos(cn(), ".local");
		if($pos > 0) {//Local on utilise json stocker dans option
			return $accessTokenDefault;
		}
		/* ------------------------------------------------------------------------------------------------- */

		/**
		 * When the OAuth2 authentication flow was completed, the user is redirected back with a code.
		 * If we received the code, we can get an access token and make API calls. Otherwise we redirect
		 * the user to the OAuth2 authorization endpoints.
		 */
		if (!empty($_GET['code'])) {
		    $code = rawurldecode($_GET['code']);
		    /**
		     * Request an access token based on the received authorization code.
		     */
		    $ch = curl_init();
		    curl_setopt($ch, CURLOPT_URL, 'https://app.teamleader.eu/oauth2/access_token');
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($ch, CURLOPT_POST, true);
		    curl_setopt($ch, CURLOPT_POSTFIELDS, [
		        'code' => $code,
		        'client_id' => $clientId,
		        'client_secret' => $clientSecret,
		        'redirect_uri' => $redirectUri,
		        'grant_type' => 'authorization_code',
		    ]);

		    $response = curl_exec($ch);
		    $data = json_decode($response, true);
		    $accessToken = $data['access_token'];
		    if($accessToken == null) {
		    	$accessToken = $accessTokenDefault;
		    }

		    return $accessToken;
		} else {
		    $query = [
		        'client_id' => $clientId,
		        'response_type' => 'code',
		        'redirect_uri' => $redirectUri,
		    ];

		    header('Location: https://app.teamleader.eu/oauth2/authorize?' . http_build_query($query));

		}
    }
}

if(!function_exists('get_invoices')){
	function get_invoices(){
		$invoices = array();
		$redirectUri = cn().'orders/invoices';
		$pos = strrpos(cn(), ".local");
		$accessToken = get_teamleader_token($redirectUri);
		if($pos > 0) {//Local on utilise json stocker dans option
			return $invoices;
		}
		/* ------------------------------------------------------------------------------------------------- */

		/**
		 * When the OAuth2 authentication flow was completed, the user is redirected back with a code.
		 * If we received the code, we can get an access token and make API calls. Otherwise we redirect
		 * the user to the OAuth2 authorization endpoints.
		 */
		if (!empty($accessToken)) {
		    /**
		     * Get the user identity information using the access token.
		     */
		    $ch = curl_init();
		    curl_setopt($ch, CURLOPT_URL, 'https://api.teamleader.eu/invoices.list');
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $accessToken]);

		    $response = curl_exec($ch);
		    if($response) {
		    	get_option('invoices_json', $response);
		    }
		    $invoices = json_decode($response, true);
		    return $invoices;
		} else {
		    header('Location: ' . $redirectUri);

		}
    }
}


if(!function_exists('get_social_accounts')){
	function get_social_accounts($social_name = "", $accounts_tmp = array()){
		$CI = &get_instance();

		if(empty($accounts_tmp)){
			$accounts_tmp = $CI->input->post("accounts");
		}

		$accounts = array();
		if(!empty($accounts_tmp)){
			foreach ($accounts_tmp as $value) {
				if(strpos($value, $social_name.'-') !== false) {
					$accounts[] = str_replace($social_name.'-', "", $value);
				}
			}
		}

		return $accounts;
	}
}

if(!function_exists('get_value')){
	function get_value($data, $key, $parseArray = false, $return = false){
		if(is_string($data)){
			$data = json_decode($data);
		}

		if(is_object($data)){
			if(isset($data->$key)){
				if($parseArray){
					return (array)$data->$key;
				}else{
					return $data->$key;
				}
			}
		}else if(is_array($data)){
			if(isset($data[$key])){
				return $data[$key];
			}
		}else{
			return $data;
		}
		
		return $return;
	}
}

if(!function_exists('get_secure')){
	function get_secure($name = ""){
		$CI = &get_instance();
		return filter_input_xss($CI->input->get(trim($name)));
	}
}

if(!function_exists('remove_empty_value')){
	function remove_empty_value($data){
		if(!empty($data)){
			return array_filter($data, function($value) {
			    return ($value !== null && $value !== false && $value !== ''); 
			});
		}else{
			return false;
		}
	}
}

if(!function_exists('get_random_value')){
	function get_random_value($data){
		if(is_array($data) && !empty($data)){
			$index = array_rand($data);
			return $data[$index];
		}else{
			return false;
		}
	}
}

if(!function_exists('get_random_values')){
	function get_random_values($data, $limit){
		if(is_array($data) && !empty($data)){
			shuffle($data);
			if(count($data) < $limit){
				$limit = count($data);
			}

			return array_slice($data, 0, $limit);
		}else{
			return false;
		}
	}
}

if(!function_exists("utf8ize")){
	function utf8ize($d) {
	    if (is_array($d)) {
	        foreach ($d as $k => $v) {
	            $d[$k] = utf8ize($v);
	        }
	    } else if (is_string ($d)) {
	        return utf8_encode($d);
	    }
	    return $d;
	}
}


if(!function_exists('specialchar_decode')){
	function specialchar_decode($input){
		$input = str_replace("\\'", "'", $input);
		$input = str_replace('\"', '"', $input);
        $input = htmlspecialchars_decode($input, ENT_QUOTES);
		return $input;
	}
}

if(!function_exists('get_proxy')){
	function get_proxy($table, $user_proxies, $data){
		$CI = &get_instance();

		$default_proxy = $CI->model->get_proxies($table, 0, "id", true, $data);
		$system_proxy = (empty($data))?$default_proxy:$data->default_proxy;

		if(get_option('user_proxy', 1) == 1 && $user_proxies != ""){
			return (object)array(
				"use"    => $user_proxies,
				"system" => $system_proxy
			);
		}

		if(get_option('system_proxy', 1) == 1){
			return (object)array(
				"use"    => $CI->model->get_proxies($table, $system_proxy, "address", true, $data),
				"system" => $system_proxy
			);
		}

		return (object)array(
			"use"    => "",
			"system" => ""
		);
	}
}

if(!function_exists('filter_input_xss')){
	function filter_input_xss($input){
        $input = htmlspecialchars($input, ENT_QUOTES);
		return $input;
	}
}

if(!function_exists('ms')){
	function ms($array){
		print_r(json_encode($array));
		exit(0);
	}
}

if (!function_exists('ids')) {
	function ids(){
		$CI = &get_instance();
		return md5($CI->encryption->encrypt(time()));
	};
}

if (!function_exists('session')){
	function session($input){
		$CI = &get_instance();
		$CI->load->library('session');
		return $CI->session->userdata($input);
	}
}

if (!function_exists('set_session')){
	function set_session($name,$input){
		$CI = &get_instance();
		return $CI->session->set_userdata($name,$input);
	}
}

if (!function_exists('unset_session')){
	function unset_session($name){
		$CI = &get_instance();
		return $CI->session->unset_userdata($name);
	}
}

if (!function_exists('encrypt_encode')) {
	function encrypt_encode($text){
		$CI = &get_instance();
		return $CI->encryption->encrypt($text);
	};
}

if (!function_exists('encrypt_decode')) {
	function encrypt_decode($key){
		$CI = &get_instance();
		return $CI->encryption->decrypt($key);
	};
}

if (!function_exists('segment')){
	function segment($index){ 
		$CI = &get_instance();
        return $CI->uri->segment($index);
	}
}

if (!function_exists('get_status')){
	function get_status($status){ 
		// Status des posts
		switch ($status) {
			case ST_PLANIFIED:
				return lang("PLANIFIED");
				break;
			case ST_PUBLISHED:
				return lang("PUBLISHED");
				break;
			case ST_FAILED:
				return lang("FAILED");
				break;
			case ST_INVALID:
				return lang("INVALID");
				break;
			case ST_DRAFT:
				return lang("DRAFT");
				break;
			
			case ST_WAITTING:
				return lang("WAITTING");
				break;
			case ST_DELETED:
				return lang("DELETED");
				break;
			default:
				return '';
				break;
		}
	}
}

if (!function_exists('cn')) {
	function cn($module=""){
		return PATH.$module;
	};
}

if (!function_exists('load_404')) {
	function load_404(){
		$CI = &get_instance();
		return	$CI->load->view("layouts/error_404.php");
	};
}


if(!function_exists('login_as_user')){
	function login_as_user($uid, $remmove = false){
		if(!$remmove){
			$uid_tmp = session("uid");
			set_session("uid_tmp", $uid_tmp);
			set_session("uid", $uid);
		}else{
			$uid_tmp = session("uid_tmp");
			if($uid_tmp){
				unset_session("uid_tmp");
				set_session("uid", $uid_tmp);
			}
		}
	}
}

if (!function_exists('time_elapsed_string')) {
	function time_elapsed_string($datetime, $full = false) {
	    $now = new DateTime;
	    $ago = new DateTime($datetime);
	    $diff = $now->diff($ago);

	    $diff->w = floor($diff->d / 7);
	    $diff->d -= $diff->w * 7;

	    $string = array(
	        'y' => 'year',
	        'm' => 'month',
	        'w' => 'week',
	        'd' => 'day',
	        'h' => 'hour',
	        'i' => 'minute',
	        's' => 'second',
	    );
	    foreach ($string as $k => &$v) {
	        if ($diff->$k) {
	            $v = $diff->$k . ' ' . lang($v . ($diff->$k > 1 ? 's' : ''));
	        } else {
	            unset($string[$k]);
	        }
	    }

	    if (!$full) $string = array_slice($string, 0, 1);
	    return $string ? implode(', ', $string) . ' '.lang('ago') : lang('just_now');
	}
}

if (!function_exists('ajax_page')) {
	function ajax_page(){
		$CI = &get_instance();
		if(!post()){
			$CI = &get_instance();
			$CI->load->view("layouts/error_404.php");
			return false;
		}else{
			return true;
		}
	};
}

if (!function_exists('require_all')) {
	function require_all($dir = "", $depth=0) {
		if($dir == ""){
			$segment = segment(1);
			$dir = APPPATH."../public/".$segment."/config/constants/";
		}

	    // require all php files
	    $scan = glob("$dir/*");
	    foreach ($scan as $path) {
	        if (preg_match('/\.php$/', $path)) {
	            require_once $path;
	        }
	        elseif (is_dir($path)) {
	            require_all($path, $depth+1);
	        }
	    }
	}
}

if (!function_exists('get_all_file_from_folder')) {
	function get_all_file_from_folder($dir = "") {
		$data = array();
		if($dir == ""){
			$segment = segment(1);
			$dir = APPPATH."../public/".$segment."/config/constants/";
		}

	    // require all php files
	    $scan = glob("$dir/*");
	    foreach ($scan as $path) {
	        if (preg_match('/\.php$/', $path)) {
	        	$data[] = $path;
	        }
	    }

	    return $data;
	}
}

if (!function_exists('get_path_module')) {
	function get_path_module(){
		$CI = &get_instance();
		return APPPATH.'modules/'.$CI->router->fetch_module().'/';
	}
}

if (!function_exists('folder_size')) {
	function folder_size($dir){
	    $size = 0;
	    foreach (glob(rtrim($dir, '/').'/*', GLOB_NOSORT) as $each) {
	        $size += is_file($each) ? filesize($each) : folderSize($each);
	    }
	    return $size;
	}
}

if (!function_exists('pr')) {
    function pr($data, $type = 0) {
        print '<pre>';
        print_r($data);
        print '</pre>';
        if ($type != 0) {
            exit();
        }
    }
}

if(!function_exists('pr_sql')){
	function pr_sql($type=0){
		$CI = &get_instance();
		$sql = $CI->db->last_query();
		pr($sql,$type);
	}
}

if (!function_exists('export_csv')) {
	function export_csv($table_name){
		$CI = &get_instance();
        $CI->load->dbutil();
        $CI->load->helper('file');
        $CI->load->helper('download');
        $delimiter = ",";
        $newline = "\r\n";
        $query = $CI->db->query("SELECT * FROM ".$table_name);
        $filename = $table_name.date("-d-m-Y", strtotime(NOW)).".csv";
        $data = $CI->dbutil->csv_from_result($query, $delimiter, $newline);
        force_download($filename, "\xEF\xBB\xBF".$data);
	}
}
if (!function_exists('export_csv_sql')) {
	function export_csv_sql($query){
		$CI = &get_instance();
        $CI->load->dbutil();
        $CI->load->helper('file');
        $CI->load->helper('download');
        $delimiter = ",";
        $newline = "\r\n";
        $query = $CI->db->query($query);
        $filename = $table_name.date("-d-m-Y", strtotime(NOW)).".csv";
        $data = $CI->dbutil->csv_from_result($query, $delimiter, $newline);
        force_download($filename, "\xEF\xBB\xBF".$data);
	}
}

if(!function_exists("convert_datetime")){
	function convert_datetime($datetime){
		return date("d/m/y", strtotime($datetime));
	}
}

if(!function_exists("convert_date")){
	function convert_date($date){
		return date("d/m/y", strtotime($date));
	}
}

if(!function_exists("convert_datetime_sql")){
	function convert_datetime_sql($datetime){
		return date("Y-m-d H:i:s", get_to_time($datetime));
	}
}

if(!function_exists("convert_date_sql")){
	function convert_date_sql($date){
		return date("Y-m-d", get_to_time($date));
	}
}

if(!function_exists("validateDate")){
	function validateDate($date, $format = 'Y-m-d'){
	    $d = DateTime::createFromFormat($format, $date);
	    return $d && $d->format($format) == $date;
	}
}

if(!function_exists("get_to_time")){
	function get_to_time($date){
		if(is_numeric($date)){
			return $date;
		}else{
			return strtotime(str_replace('/', '-', $date));
		}
	}
}

if(!function_exists("get_to_day")){
	function get_to_day($date, $fulltime = true){
		$strtime = strtotime(str_replace('/', '-', $date));
		if($fulltime){
			return date("Y-m-d H:i:s", $strtime);
		}else{
			return date("Y-m-d", $strtime);
		}
	}
}

if(!function_exists("row")){
	function row($data, $field){
		if(is_object($data)){
			if(isset($data->$field)){
				return $data->$field;
			}else{
				return "";
			}
		}

		if(is_array($data)){
			if(isset($data[$field])){
				return $data[$field];
			}else{
				return "";
			}
		}
	}
}

if (!function_exists('tz_list')){
	function tz_list() {
	  	$zones_array = array();
	  	$timestamp = time();
	  	foreach(timezone_identifiers_list() as $key => $zone) {
	   		date_default_timezone_set($zone);
	   		$zones_array[$key]['zone'] = $zone;
	    	$zones_array[$key]['time'] = '(UTC ' . date('P', $timestamp).") ".$zone;
	    	$zones_array[$key]['sort'] = date('P', $timestamp);
	  	}


	  	usort($zones_array, function($a, $b) {
	  		return strcmp($a["sort"], $b["sort"]);
		});
		
	  	return $zones_array;
	}
}


if (!function_exists('tz_convert')){
	function tz_convert($timezone) {
		date_default_timezone_set($timezone);
	  	$zones_array = array();
	  	$timestamp = time();
	  	foreach(timezone_identifiers_list() as $key => $zone) {
	   		if($zone == $timezone){
	   			return date('P', $timestamp);
	   		}
	  	}
		
	  	return false;
	}
}

if (!function_exists('get_line_with_string')){
	function get_line_with_string($fileName, $str) {
		if(is_file($fileName)){
	    	$lines = file($fileName);
		    foreach ($lines as $lineNumber => $line) {
		        if (strpos($line, $str) !== false) {
		            return trim(str_replace("/*", "", str_replace("*/", "", $line)));
		        }
		    }
		}else{
			$lines = $fileName;
		}
		
	    return false;
	}
}

if (!function_exists('get_timezone_user')){
	function get_timezone_user($datetime, $convert = false, $uid = 0){
		$datetime = get_to_time($datetime);
		$datetime = is_numeric($datetime)?date("Y-m-d H:i:s", $datetime):$datetime;
		$userid= user_or_cm();
		$uid = $userid?$userid:$uid;
		$CI = &get_instance();

		if(empty($CI->help_model)){
			$CI->load->model('model', 'help_model');
		}

		$user = $CI->help_model->get("timezone", USERS, "id = '".$uid."'");
		if(!empty($user)){
			$date = new DateTime($datetime, new DateTimeZone(TIMEZONE));
			$date->setTimezone(new DateTimeZone($user->timezone));
			$result = $date->format('Y-m-d H:i:s');
			return $convert?convert_datetime($result):$result;
		}else{
			return $convert?convert_datetime($datetime):$result;
		}
	}
}

if (!function_exists('get_user')){
	function get_user(){
		$uid = session("uid");
		$CI = &get_instance();

		if(empty($CI->help_model)){
			$CI->load->model('model', 'help_model');
		}

		$user = $CI->help_model->get("*", USERS, "id = '".$uid."'");
		return $user;
	}
}

if (!function_exists('get_timezone_system')){
	function get_timezone_system($datetime, $convert = false, $uid = 0){
		$datetime = get_to_time($datetime);
		$datetime = is_numeric($datetime)?date("Y-m-d H:i:s", $datetime):$datetime;

		$uid = session("uid")?user_or_cm():$uid;
		$CI = &get_instance();

		if(empty($CI->help_model)){
			$CI->load->model('model', 'help_model');
		}

		$user = $CI->help_model->get("timezone", USERS, "id = '".$uid."'");
		if(!empty($user)){
			$date = new DateTime($datetime, new DateTimeZone($user->timezone));
			$date->setTimezone(new DateTimeZone(TIMEZONE));
			$result = $date->format('Y-m-d H:i:s');  
			return $convert?convert_datetime($result):$result;
		}else{
			return $convert?convert_datetime($datetime):$result;
		}
	}
}

if(!function_exists('get_role')){
	function get_role(){
		if(strpos(current_url(), "/cron") === FALSE || segment(1) == "cron"){
			$CI = &get_instance();

			if(empty($CI->help_model)){
				$CI->load->model('model', 'help_model');
			}

			$user = $CI->help_model->get("admin", USERS, "id = '".session("uid")."'");
			if((!empty($user) && $user->admin == 1) || ( (is_manager() || is_responsable())  && segment(1) == "users") ){
				return true;
			}else{
				return false;
			}
		}
	}
}

if(!function_exists('is_admin')){
	function is_admin(){
		$return = false;
		if(strpos(current_url(), "/cron") === FALSE || segment(1) == "cron"){
			$CI = &get_instance();

			if(empty($CI->help_model)){
				$CI->load->model('model', 'help_model');
			}

			$user = $CI->help_model->get("*", USERS, "id = '".session("uid")."'");
			if(!empty($user) && ($user->admin == 1 || $user->role == 'admin')){
				$return = true;
			}
		}
		return $return;
	}
}
if(!function_exists('is_responsable')){
	function is_responsable(){
		$return = false;
		if(strpos(current_url(), "/cron") === FALSE || segment(1) == "cron"){
			$CI = &get_instance();

			if(empty($CI->help_model)){
				$CI->load->model('model', 'help_model');
			}

			$user = $CI->help_model->get("*", USERS, "id = '".session("uid")."'");
			if(!empty($user) && ($user->admin == 1 || $user->role == 'responsable')){
				$return = true;
			}
		}
		return $return;
	}
}

if(!function_exists('is_manager')){
	function is_manager(){
		$return = false;
		if(strpos(current_url(), "/cron") === FALSE || segment(1) == "cron"){
			$CI = &get_instance();

			if(empty($CI->help_model)){
				$CI->load->model('model', 'help_model');
			}

			$user = $CI->help_model->get("*", USERS, "id = '".session("uid")."'");
			if(!empty($user) && $user->role == 'manager'){
				$return = true;
			}
		}
		return $return;
	}
}

if(!function_exists('is_customer')){
	function is_customer(){
		$return = false;
		if(strpos(current_url(), "/cron") === FALSE || segment(1) == "cron"){
			$CI = &get_instance();

			if(empty($CI->help_model)){
				$CI->load->model('model', 'help_model');
			}

			$user = $CI->help_model->get("*", USERS, "id = '".session("uid")."'");
			if(!empty($user) && $user->role == 'customer'){
				$return = true;
			}
		}
		return $return;
	}
}
if(!function_exists('is_resp')){
	function is_resp(){
		$return = false;
		if(strpos(current_url(), "/cron") === FALSE || segment(1) == "cron"){
			$CI = &get_instance();

			if(empty($CI->help_model)){
				$CI->load->model('model', 'help_model');
			}

			$user = $CI->help_model->get("*", USERS, "id = '".session("uid")."'");
			if(!empty($user) && $user->role == 'responsable'){
				$return = true;
			}
		}
		return $return;
	}
}
if(!function_exists('getCustomers')){
	function getCustomers($user = null) {
		$CI = &get_instance();
		$group_ids = $groups = array();
		if(empty($CI->help_model)){
			$CI->load->model('model', 'help_model');
		}
		if($user != null) {
			if($user->role == 'responsable') {
				$groups = $CI->help_model->getList("id_group", 'responsable_group', "id_user = '".$user->ids."'");
			} elseif ($user->role == 'manager') {
				$groups = $CI->help_model->getList("id_group", 'manager_group', "id_user = '".$user->ids."'");
			}
			if($groups) {
				foreach ($groups as $key => $grp) {
					$group_ids[] = "'".$grp->id_group."'";
				}
				$group_ids = implode(',', $group_ids);
			}
		}
		$users = $CI->help_model->getCustomers_data($group_ids);
		return $users;
	}
}

if(!function_exists('getSumPackCustomers')){
	function getSumPackCustomers($user = null) {
		$count = 0;
		$CI = &get_instance();
		if(empty($CI->help_model)){
			$CI->load->model('model', 'help_model');
		}
		$customers = getCustomers_sidebar($user);
		if($customers) {
			foreach ($customers as $key => $customer) {
				$pack = $CI->help_model->get("*", PACKAGES, "id = '".$customer->package."'");
				$count += (int)$pack->number_posts;
			}
		}
		return $count;
	}
}

if(!function_exists('dump')){
	function dump($array = null) {
		echo "<pre>";
		var_dump($array);exit;
	}
}


if(!function_exists('getCustomers_sidebar')){
	function getCustomers_sidebar($user = 0) {
		$CI = &get_instance();
		$group_ids = $groups = array();
		if(empty($CI->help_model)){
			$CI->load->model('model', 'help_model');
		}
		$users = $CI->help_model->getCustomers_data_sidebar($user);
		return $users;
	}
}
if(!function_exists('get_accounts')){
	function get_accounts($groupid=null) {
		$CI = &get_instance();
		$group_ids = $groups = array();
		if(empty($CI->help_model)){
			$CI->load->model('model', 'help_model');
		}
		return $CI->help_model->get_account_data($groupid);
	}
}
if(!function_exists('getUsersByRole')){
	function getUsersByRole($from = "") {
		$CI = &get_instance();
		$group_ids = $groups = array();
		if(empty($CI->help_model)){
			$CI->load->model('model', 'help_model');
		}
		$users = $CI->help_model->getUsersByRole_data($from);
		return $users;
	}
}
if(!function_exists('getUsersByRolelast3_month')){
	function getUsersByRolelast3_month($from = "") {
		$CI = &get_instance();
		$group_ids = $groups = array();
		if(empty($CI->help_model)){
			$CI->load->model('model', 'help_model');
		}
		$users = $CI->help_model->getUsersByRole_last3_month($from);
		return $users;
	}
}

if(!function_exists('getManager')){
	function getManager($user = null) {
		$CI = &get_instance();
		$group_ids = $groups = array();
		if(empty($CI->help_model)){
			$CI->load->model('model', 'help_model');
		}
		if($user != null) {
			$groups = $CI->help_model->getList("id_group", 'responsable_group', "id_user = '".$user->ids."'");
			if($groups) {
				foreach ($groups as $key => $grp) {
					$group_ids[] = "'".$grp->id_group."'";
				}
				$group_ids = implode(',', $group_ids);
			}
		}
		$users = $CI->help_model->getManager($group_ids);
		return $users;
	}
}

if(!function_exists('getGroups')){
	function getGroups($user = null) {
		$CI = &get_instance();
		$groups = array();
		if(empty($CI->help_model)){
			$CI->load->model('model', 'help_model');
		}
		$group_ids = array();
		
		if($user->role == 'responsable') {
			$groups = $CI->help_model->getList("id_group", 'responsable_group', "id_user = '".$user->ids."'");
		} elseif ($user->role == 'manager') {
			$groups = $CI->help_model->getList("id_group", 'manager_group', "id_user = '".$user->ids."'");
		} elseif ($user->role == 'customer') {
			$groups = $CI->help_model->getList("id_group", 'user_group', "id_user = '".$user->ids."'");
		}
		if($groups) {
			foreach ($groups as $key => $grp) {
				$group_ids[] = "'".$grp->id_group."'";
			}
			$group_ids = implode(',', $group_ids);
		}
		if($group_ids != null) {
			$groups = $CI->help_model->getGroups($group_ids);
			return  $groups;
		} else {
			return array();
		}
		
	}
}
if(!function_exists('get_controller_role')){
	function get_controller_role(){
		if(!get_role()){
			redirect(cn());
		}
	}
}

if(!function_exists('get_schedule_report')){
	function get_schedule_report($table, $status){
		$CI = &get_instance();

		if(empty($CI->help_model)){
			$CI->load->model('model', 'help_model');
		}

		$data = $CI->help_model->schedule_report($table, $status);

		return $data;
	}
}
if(!function_exists('get_count_posts')){
	function get_count_posts($userid,$status){
		$CI = &get_instance();
		if(empty($CI->help_model)){
			$CI->load->model('model', 'help_model');
		}
		
		$data = $CI->help_model->count_posts($userid,$status);

		return $data;
	}
}
if(!function_exists('get_count_posts_user')){
	function get_count_posts_user($userid,$status){
		$CI = &get_instance();
		if(empty($CI->help_model)){
			$CI->load->model('model', 'help_model');
		}
		
		$data = $CI->help_model->count_posts_user($userid,$status);

		return $data;
	}
}

if(!function_exists('get_count_posts_manager')){
	function get_count_posts_manager($user,$status){
		$CI = &get_instance();
		$ids = array();
		$count = 0;

		if(empty($CI->help_model)){
			$CI->load->model('model', 'help_model');
		}
		$groups = $CI->help_model->fetch("id_group", "manager_group", "id_user = '".$user."'");
		if($groups) {
			foreach ($groups as $key => $grp) {
				$users_gr = $CI->help_model->fetch("id_user", "user_group", "id_group = '".$grp->id_group."'");
				foreach ($users_gr as $key => $customer) {
					$userinfo = $CI->help_model->get("id", USERS, "ids = '".$customer->id_user."'");
					$ct = $CI->help_model->count_posts($userinfo->id,$status);
					if($ct) {
						$count += (int)$ct;
					}
				}
			}
		}
		if($count) {
			return $count;
		}
		return 0;
	}
}

if(!function_exists('get_cm_by_customer')){
	function get_cm_by_customer($customer){
		$CI = &get_instance();
		$group_ids = array();

		if(empty($CI->help_model)){
			$CI->load->model('model', 'help_model');
		}

		$groups = $CI->help_model->getList("id_group", 'user_group', "id_user = '".$customer->ids."'");
		if($groups) {
			foreach ($groups as $key => $grp) {
				$group_ids[] = "'".$grp->id_group."'";
			}
			$group_ids = implode(',', $group_ids);
		}
		$managers = $CI->help_model->getManager($group_ids);

		return $managers;
	}
}
if(!function_exists('get_count_posts_socials')){
	function get_count_posts_socials($userid,$status){
		$CI = &get_instance();

		if(empty($CI->help_model)){
			$CI->load->model('model', 'help_model');
		}

		$data = $CI->help_model->count_posts_socials($userid,$status);

		return $data;
	}
}
if(!function_exists('get_posts_solde')){
	function get_posts_solde($userid){
		$CI = &get_instance();

		if(empty($CI->help_model)){
			$CI->load->model('model', 'help_model');
		}

		$data = $CI->help_model->posts_solde($userid);

		return $data;
	}
}

if(!function_exists('get_schedules_report')){
	function get_schedules_report($status){
		$CI = &get_instance();

		if(empty($CI->help_model)){
			$CI->load->model('model', 'help_model');
		}

		$data = $CI->help_model->schedules_report($status);

		return $data;
	}
}
if(!function_exists('get_notification_count')){
	function get_notification_count(){
		$CI = &get_instance();

		if(empty($CI->help_model)){
			$CI->load->model('model', 'help_model');
		}

		$data = $CI->help_model->notification_count();

		return $data;
	}
}

if(!function_exists("get_option")){
	function get_option($key, $value = ""){
		$CI = &get_instance();
		
		if(empty($CI->help_model)){
			$CI->load->model('model', 'help_model');
		}
		$option = $CI->help_model->get("value", OPTIONS, "name = '{$key}'");
		if(empty($option)){
			$CI->db->insert(OPTIONS, array("name" => $key, "value" => $value));
			return $value;
		}else{
			return $option->value;
		}
	}
}

if(!function_exists("update_option")){
	function update_option($key, $value){
		$CI = &get_instance();
		
		if(empty($CI->help_model)){
			$CI->load->model('model', 'help_model');
		}
		
		$option = $CI->help_model->get("value", OPTIONS, "name = '{$key}'");
		if(empty($option)){
			$CI->db->insert(OPTIONS, array("name" => $key, "value" => $value));
		}else{
			$CI->db->update(OPTIONS, array("value" => $value), array("name" => $key));
		}
	}
}

if(!function_exists("get_groups")){
	function get_groups(){
		$CI = &get_instance();
		
		if(empty($CI->help_model)){
			$CI->load->model('model', 'help_model');
		}
		
		$groups = $CI->help_model->fetch("*", "general_groups", "uid = '".session("uid")."'");
		return $groups;
	}
}

if(!function_exists("delete_option")){
	function delete_option($key){
		$CI = &get_instance();
		$CI->db->delete(OPTIONS, array("name" => $key));
	}
}

if(!function_exists("get_setting")){
	function get_setting($key, $value = "", $uid = 0){
		$CI = &get_instance();
		if(session("uid")){
			$uid = session("uid");
		}

		if($uid != 0){
			
			if(empty($CI->help_model)){
				$CI->load->model('model', 'help_model');
			}

			$setting = $CI->help_model->get("settings", USERS, "id = '".$uid."' ")->settings;
			$option = json_decode($setting);

			if(is_array($option) || is_object($option)){
				$option = (array)$option;

				if( isset($option[$key]) ){
					return row($option, $key);
				}else{
					$option[$key] = $value;
					$CI->db->update(USERS, array("settings" => json_encode($option)), array("id" => $uid) );
					return $value;
				}
			}else{ 
				$option = json_encode(array($key => $value));
				$CI->db->update(USERS, array("settings" => $option), array("id" => $uid));
				return $value;
			}
		}
	}
}

if(!function_exists("update_setting")){
	function update_setting($key, $value, $uid = 0){
		$CI = &get_instance();
		if(session("uid")){
			$uid = session("uid");
		}

		if($uid != 0){
			
			if(empty($CI->help_model)){
				$CI->load->model('model', 'help_model');
			}

			$setting = $CI->help_model->get("settings", USERS, "id = '".$uid."' ")->settings;
			$option = json_decode($setting);
			if(is_array($option) || is_object($option)){
				$option = (array)$option;
				if( isset($option[$key]) ){
					$option[$key] = $value;
					$CI->db->update(USERS, array("settings" => json_encode($option)), array("id" => $uid) );
					return true;
				}
			}
		}
		return false;
	}
}

if(!function_exists("get_field")){
	function get_field($table, $id, $field){
		$CI = &get_instance();

		if(empty($CI->help_model)){
			$CI->load->model('model', 'help_model');
		}
		$item =$CI->help_model->get("*", $table, "id = '{$id}'");

		if(!empty($item) && isset($item->$field)){
			return $item->$field;
		}else{
			return false;
		}
	}
}

if(!function_exists("get_payment")){
	function get_payment(){
		if (is_dir(APPPATH."modules/payment")) {
			return true;
		}else{
			return false;
		}
	}
}

if(!function_exists("get_upload_folder")){
	function get_upload_folder(){
		$path = APPPATH."../assets/uploads/user" . user_or_cm()."/";
		if (!file_exists($path)) {
			$uold     = umask(0);
	    	mkdir($path, 0777);
			umask($uold);

	    	file_put_contents($path."index.html", "<h1>404 Not Found</h1>");
	    }
	}
}

if ( ! function_exists('get_info_link')){
	function get_info_link($url)
	{	

		$info = array(
			'title' => "",
			'description' => "",
			'image' => "",
			'host' => ""
		);

		$parse_url = @parse_url($url);
		if(isset($parse_url["host"])){
			$info['host'] = $parse_url["host"];
		}

		// Check if the URL is youtube video
		$youtube_reg = "/(youtube.com|youtu.be)\/(watch)?(\?v=)?(\S+)?/";
		if(preg_match($youtube_reg, $url, $match)){
			//https://www.youtube.com/oembed?url=http://www.youtube.com/watch?v=B4CRkpBGQzU&format=json
			$result = get_curl("https://www.youtube.com/oembed?url=".$url."&format=json");
			$result = json_decode($result);
			if(!empty($result)){

				if(isset($result->title))
					$info['title'] = $result->title;

				if(isset($result->thumbnail_url))
					$info['image'] = $result->thumbnail_url;
			}
			
			return $info;
		}
		
		$result = get_curl($url);
		$doc = new DOMDocument();
		@$doc->loadHTML(mb_convert_encoding($result, 'HTML-ENTITIES', 'UTF-8'));
		$title = $doc->getElementsByTagName('title');
		$metas = $doc->getElementsByTagName('meta');

		$info["title"] = isset($title->item(0)->nodeValue) ? $title->item(0)->nodeValue : "";

		for ($i = 0; $i < $metas->length; $i++){
		    $meta = $metas->item($i);
		    
		    if($info['description'] == ""){
			    if(strtolower($meta->getAttribute('name')) == 'description'){
			        $info['description'] = $meta->getAttribute('content');
			    }
			}
			if($info['image'] == ""){
				if($meta->getAttribute('property') == 'og:image'){
			        $info['image'] = $meta->getAttribute('content');
			    }
			}
		}

		if($info['description'] == ""){
			for ($i = 0; $i < $metas->length; $i++){
			    $meta = $metas->item($i);
				if(strtolower($meta->getAttribute('property')) == 'og:description'){
			       	$info['description'] = $meta->getAttribute('content');
			   	}
			}
		}

		if($info['description'] == ""){
			for ($i = 0; $i < $metas->length; $i++){
		    	$meta = $metas->item($i);
				$body = $doc->getElementsByTagName('body');
				$text = strip_tags($body->item(0)->nodeValue);
				$dots = "";
				if(strlen(utf8_decode($text))>250) $dots = "...";
				$text = mb_substr(stripslashes($text),0,250, 'utf-8');
				$info['description'] = $text.$dots;
			}
		}

		return $info;
	}
}

if(!function_exists("get_client_ip")){
	function get_client_ip() {
	    $ipaddress = '';
	    if (isset($_SERVER['HTTP_CLIENT_IP']))
	        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    else if(isset($_SERVER['HTTP_X_FORWARDED']))
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
	        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	    else if(isset($_SERVER['HTTP_FORWARDED']))
	        $ipaddress = $_SERVER['HTTP_FORWARDED'];
	    else if(isset($_SERVER['REMOTE_ADDR']))
	        $ipaddress = $_SERVER['REMOTE_ADDR'];
	    else
	        $ipaddress = 'UNKNOWN';

	    return $ipaddress;
	}
}

if(!function_exists("info_client_ip")){
	function info_client_ip(){
		$result = get_curl("https://timezoneapi.io/api/ip");

		$result = json_decode($result);
		if(!empty($result)){
			return $result;
		}
		return false;
	}
}

if(!function_exists("get_curl")){
	function get_curl($url){
		$user_agent='Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420.1 (KHTML, like Gecko) Version/3.0 Mobile/3B48b Safari/419.3';

		$headers = array
		(
		    'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
		    'Accept-Language: en-US,fr;q=0.8;q=0.6,en;q=0.4,ar;q=0.2',
		    'Accept-Encoding: gzip,deflate',
		    'Accept-Charset: utf-8;q=0.7,*;q=0.7',
		    'cookie:datr=; locale=en_US; sb=; pl=n; lu=gA; c_user=; xs=; act=; presence='
		); 

        $ch = curl_init( $url );

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST , "GET");
        curl_setopt($ch, CURLOPT_POST, false);     
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_REFERER, base_url());

        $result = curl_exec( $ch );
       
        curl_close( $ch );

        return $result;
	}
}

if(!function_exists("get_js")){
	function get_js($js_files = array()){
		$core = APPPATH."../assets/js/core.js";

		if(!file_exists($core)){
			$minifier = new MatthiasMullie\Minify\JS();
			foreach ($js_files as $file) {
				$minifier->add(APPPATH."../".$file);
			}

			$minifier->minify($core);
			$minifier->add($core);
		}else{

			$mod_date=date("F d Y H:i:s.", filemtime($core));
			$date = strtotime(date("Y-m-d", strtotime(NOW)));
			$mod_date = strtotime(date("Y-m-d", strtotime($mod_date)));

			if($mod_date < $date){
				$minifier = new MatthiasMullie\Minify\JS();
				foreach ($js_files as $file) {
					$minifier->add(APPPATH."../".$file);
				}

				$minifier->minify($core);
				$minifier->add($core);
			}

		}
		echo BASE."assets/js/core.js";
	}
}

if(!function_exists("get_css")){
	function get_css($css_files = array()){
		$core = APPPATH."../assets/css/core.css";

		if(!file_exists($core)){
			$minifier = new MatthiasMullie\Minify\CSS();
			foreach ($css_files as $file) {
				$minifier->add(APPPATH."../".$file);
			}
			$minifier->minify($core);
			$minifier->add($core);
		}else{

			$mod_date=date("F d Y H:i:s.", filemtime($core));
			$date = strtotime(date("Y-m-d", strtotime(NOW)));
			$mod_date = strtotime(date("Y-m-d", strtotime($mod_date)));

			if($mod_date < $date){
				$minifier = new MatthiasMullie\Minify\CSS();
				foreach ($css_files as $file) {
					$minifier->add(APPPATH."../".$file);
				}

				$minifier->minify($core);
				$minifier->add($core);
			}

		}
		
		echo '<link rel="stylesheet" type="text/css" href="'.BASE.'assets/css/core.css">';
	}
}

if (!function_exists('getEmailTemplate')) {
	function getEmailTemplate($key=""){
		$result = (object)array();
		$result->subject = '';
		$result->content = '';
		if(!empty($key)){
			switch ($key) {
				case 'activate':
					$result->subject = "Hello {full_name}! Activation your account";
					$result->content = "Welcome to {website_name}! \r\n\r\nHello {full_name},  \r\n\r\nThank you for joining! We're glad to have you as community member, and we're stocked for you to start exploring our service.  \r\n All you need to do is activate your account: \r\n  {activation_link} \r\n\r\nThanks and Best Regards!";
					return $result;
					break;
				case 'welcome':
					$result->subject = "Hi {full_name}! Getting Started with Our Service";
					$result->content = "Hello {full_name}! \r\n\r\nCongratulations! \r\nYou have successfully signed up for our service. \r\nYou have got a trial package, starting today. \r\nWe hope you enjoy this package! We love to hear from you, \r\n\r\nThanks and Best Regards!";
					return $result;
					break;
				case 'forgot_password':
					$result->subject = "Hi {full_name}! Password Reset";
					$result->content = "Hi {full_name}! \r\n\r\nSomebody (hopefully you) requested a new password for your account. \r\n\r\nNo changes have been made to your account yet. \r\nYou can reset your password by click this link: \r\n{recovery_password_link}. \r\n\r\nIf you did not request a password reset, no further action is required. \r\n\r\nThanks and Best Regards!";
					return $result;
					break;
				case 'payment':
					$result->subject = "Hi {full_name}, Thank you for your payment";
					$result->content = "Hi {full_name}, \r\n\r\nYou just completed the payment successfully on our service. \r\nThank you for being awesome, we hope you enjoy your package. \r\n\r\nThanks and Best Regards!";
					return $result;
					break;
				case 'game':
					$result->subject = "Hi {full_name}, Thank you for your participation";
					$result->content = "Hi {full_name}, \r\n\r\nYou just completed the game successfully on our service. \r\nThank you for being awesome, we hope you enjoy. \r\n\r\nThanks and Best Regards!";
					return $result;
					break;
				case 'parrain':
					$result->subject = "{game_name}";
					$result->content = "Hi {full_name}, \r\n\r\nInvite you to participate in the game : <a herf='{link_game}'>{game_name}</a>. \r\nWe hope you enjoy. \r\n\r\nThanks and Best Regards!";
					return $result;
					break;
				case 'reminder':
					$result->subject = "Hi {full_name}, Here's a little Reminder your Membership is expiring soon...";
					$result->content = "Dear {full_name}, \r\n\r\nYour membership with your current package will expire in {days_left} days. \r\nWe hope that you will take the time to renew your membership and remain part of our community. It couldn’t be easier - just click here to renew: {website_link} \r\n\r\nThanks and Best Regards!";
					return $result;
					break;
			}
		}
		return $result;
	}
}

class Spintax
{
    public function process( $text )
    {
    	$text = specialchar_decode($text);
        return preg_replace_callback(
            '/\{(((?>[^\{\}]+)|(?R))*)\}/x',
            array( $this, 'replace' ),
            $text
        );
    }

    public function replace( $text )
    {
        $text = $this -> process( $text[1] );
        $parts = explode( '|', $text );
        return $parts[ array_rand( $parts ) ];
    }
}

if (!function_exists('watermark')) {
	function watermark($path_image, $save_image = "", $uid = 0){
		$uid = (user_or_cm() && $uid != 0)?user_or_cm():$uid;

		$watermark_image = get_setting("watermark_image", "", $uid);
	    $watermark_size = get_setting("watermark_size", 30, $uid);
	    $watermark_opacity = get_setting("watermark_opacity", 70, $uid);
	    $watermark_position = get_setting("watermark_position", "lb", $uid);

		if(file_exists($watermark_image)){
		    $watermark = new Watermark();

		    $path_image = get_path_file($path_image);
		    $save_image = get_path_file($save_image);

		    if($save_image == ""){
				$save_image = $path_image;
			}

		    $watermark->apply($path_image, $save_image, $watermark_image, $watermark_position, $watermark_size, $watermark_opacity/100);

		    return get_link_tmp($save_image);
		}else{
			return $path_image;
		}
	}
}

if(!function_exists('team_session')){
	function team_session(){
		if(session("uid_main")){
			return session("uid_main");
		}
		return  session("uid");
	}
}
if(!function_exists('user_or_cm')){
	function user_or_cm(){
		$userid;
		if(session("cm_uid")){
			$userid = session("cm_uid");
		}else{
			$userid = session("uid");
		}
		return $userid;
	}
}

if(!function_exists('get_teamleader_access')){
	function get_teamleader_access(){

		$clientId = get_option('teamleader_id_client');
		$clientSecret = get_option('teamleader_secret_client');
		$redirectUri = cn('').'teamleader/get_access';

		if(!empty($clientId) || !empty($clientSecret)) {
			if (!empty($_GET['code'])) {

			    $code = rawurldecode($_GET['code']);

			    /**
			     * Request an access token based on the received authorization code.
			     */
			    $ch = curl_init();
			    curl_setopt($ch, CURLOPT_URL, 'https://app.teamleader.eu/oauth2/access_token');
			    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			    curl_setopt($ch, CURLOPT_POST, true);
			    curl_setopt($ch, CURLOPT_POSTFIELDS, [
			        'code' => $code,
			        'client_id' => $clientId,
			        'client_secret' => $clientSecret,
			        'redirect_uri' => $redirectUri,
			        'grant_type' => 'authorization_code',
			        'date_from' => '01-01-2019',
			    ]);

			    $response = curl_exec($ch);
			    $data = json_decode($response, true);
			    $accessToken = $data['access_token'];
			    $accessTokenRefresh = $data['refresh_token'];

			    update_option('teamleader_token', $accessToken);
			    update_option('teamleader_token_refresh', $accessTokenRefresh);
			    update_option('teamleader_code', $code);
			    // redirect(cn("settings/general/teamleader"));
			} else {

			    $query = [
			        'client_id' => $clientId,
			        'response_type' => 'code',
			        'redirect_uri' => $redirectUri,
			    ];

			    header('Location: https://app.teamleader.eu/oauth2/authorize?' . http_build_query($query));

			}
		} else {
			ms(
				array(
 				"status"  => "error",
 				"message" => lang("please_enter_secret_or_id_client")
 				)
			);
		}
		
	}
}

