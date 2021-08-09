<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class users extends MX_Controller {
	public $table;
	public $columns;
	public $module_name;
	public $module_icon;

	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');

		//Config Module
		$this->table       = "general_users";
		$this->module_name = lang("user_manager");
		$this->module_icon = "fa ft-users";
		$this->columns = array(
			"users.id as id_user"            => lang("id"),
			"email"            => lang("email"),
			"fullname"         => lang("fullname"),
			"package"          => lang("package"),
			"expiration_date"  => lang("expiration_date"),
			"login_type"       => lang("login_type"),
			"history_ip"       => lang("history_ip"),
			"status"           => lang("status"),
			"changed"          => lang("changed"),
			"created"          => lang("created"),
			"role"          => lang("role"),
			"id_team"          => lang("id_team"),
			"telephone"          => lang("telephone"),
			"new_id_teamlead"          => lang("new_id_teamlead"),
			"avatar"          => lang("avatar")
		);
	}
	public function stats() {
		$mois = (int)date('m');
		$annee = (int)date('Y');
		if($mois == 1) {
			$annee = $annee - 1;
		}
		$dateFrom = $annee."-".($mois-2)."-01";
		$dateTo = $annee."-".($mois-1)."-31";
		$data['user'] = $this->model->get("*", USERS, "id = '".session("uid")."'");
		$data['grp'] = $this->model->get_groups();
		$data['dateFrom'] = $dateFrom;
		$data['dateTo'] = $dateTo;
		$this->template->build('report', $data);
		
	}
	public function show_user($ids = ""){
		$userRole = $this->model->get("*", $this->table, "ids = '".$ids."'");
		$user = $userRole;
		
		if ($user) {
			$role = $user->role;
			$data['user'] = $user;
			if($role == 'responsable') {
				$groups = getGroups($user);
				if($groups) {
					$data['managers'] = getManager($user);
					$data['customers'] = getCustomers($user);
				}
				$data['groups'] = getGroups($user);
			} elseif($role == 'manager') {
				$data['customers'] = getCustomers();
			}
			$result = array();
			$company_id = $ids;
			$teamleader_api_group = get_option('teamleader_api_group');
			$teamleader_api_secret = get_option('teamleader_api_secret');
			$accessTokenRefresh = get_option('teamleader_token_refresh');
			$invoices = array();
			if (!empty($teamleader_api_group)) {
				
				/**
				 * Get the user identity information using the access token.
				 */
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'https://app.teamleader.eu/api/getInvoices.php');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, [
					'date_from' => '01/01/2015',
					'api_group' => $teamleader_api_group,
					'api_secret' => $teamleader_api_secret,
					'date_to' => date('d/m/Y'),
					'contact_or_company' => 'company',
					'contact_or_company_id' => $company_id,
				]);

				$response = curl_exec($ch);
				$data['invoices'] = array();
				// dump(json_decode($response, true));
				if($response) {
					$data['invoices'] = json_decode($response, true);
					foreach ($data['invoices'] as $key => $invoice) {
						$user = $this->model->get("*",USERS, "ids = '{$invoice['contact_or_company_id']}'");
						if($user){
							$result[] = $invoice;
						}
					}
				}
				// dump($data);die;
				$data['invoices'] = $result;
			}
			$data["module"]=get_class($this);
			$this->template->build('show', $data);
		}else{
			redirect(cn("users/customer"));
		}
	}

	public function companiesList() {
		$data['pag'] = get_option('companies_nbr_page');
		$this->template->build('companiesList', $data);
	}
	public function user_api(){
		$accessToken = get_option('teamleader_token');
		$invoices = array();
		$redirectUri = cn().'users/user_api';
		$pos = strrpos(cn(), ".local");
		if($pos > 0) {//Local on utilise json stocker dans option
			return false;
		} else {
			if (!empty($accessToken)) {
			    
			    /**
			     * Get the user identity information using the access token.
			     */
			    $ch = curl_init();
			    curl_setopt($ch, CURLOPT_URL, 'https://app.teamleader.eu/api/getCompanies.php');
			    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			    curl_setopt($ch, CURLOPT_POSTFIELDS, [
			        'amount' => '100',
			        'pageno' => '0',
			    ]);
			    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $accessToken]);

			    $response = curl_exec($ch);
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
	public function user_api2(){
		$acess = $this->model->refresh_token();
		$accessToken = get_option('teamleader_token');
		$invoices = array();
		$redirectUri = cn().'users/user_api';
		$pos = strrpos(cn(), ".local");
		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => "https://api.teamleader.eu/companies.list",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_POSTFIELDS =>"{
				  \"page\": {
				    \"size\": 20,
				    \"number\": 1
			  },
			  \"sort\": [
				    {
				      \"field\": \"name\"
			    }
			  ]
		}",
		CURLOPT_HTTPHEADER => array(
			"Authorization: Bearer ".$accessToken,
			"Content-Type: application/json"
		),

		));

		$response = curl_exec($curl);
		$response = json_decode($response, true);
		curl_close($curl);
		$this->maj_user_from_teamleader($response);
	}
	public function add_user_api(){
		$data = array(
			"packages"    => $this->model->fetch("name, id, ids", PACKAGES),
			"groupes"    => $this->model->fetch("name, id, ids", GROUPES),
			"result"      => $this->model->getUSer(segment(3)),       
			"module"      => get_class($this),
			"module_name" => $this->module_name,
			"module_icon" => $this->module_icon,
		);
		$this->template->build('create_api', $data);

	}
	public function getCom() {
		$acess = $this->model->refresh_token();
		$accessToken = get_option('teamleader_token');
		$invoices = array();
		$redirectUri = cn().'users/user_api';
		$pos = strrpos(cn(), ".local");
		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => "https://api.teamleader.eu/companies.list",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_POSTFIELDS =>"{
				  \"page\": {
				    \"size\": 20,
				    \"number\": 1
			  },
			  \"sort\": [
				    {
				      \"field\": \"name\"
			    }
			  ]
		}",
		CURLOPT_HTTPHEADER => array(
			"Authorization: Bearer ".$accessToken,
			"Content-Type: application/json"
		),

		));

		$response = curl_exec($curl);
		$response = json_decode($response, true);
		echo "<pre>";
		var_dump($response);exit;
		curl_close($curl);
	}
	public function add_user_byid(){
		$acess = $this->model->refresh_token();
		$accessToken = get_option('teamleader_token');
		$invoices = array();
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://api.teamleader.eu/companies.info?id=".post('id_team'),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"Authorization: Bearer ".$accessToken,
				"Content-Type: application/json"
			),
			
		));
		
		$response = curl_exec($curl);
		$response = json_decode($response, true);
		$company = $response['data'];
		curl_close($curl);
		$postal_code = $company['primary_address']['postal_code'];
		$city = $company['primary_address']['city'];
		$line_1 = $company['primary_address']['line_1'];
		if($company['addresses']){
			$postal_code = $company['addresses'][0]['address']['postal_code'];
			$city = $company['addresses'][0]['address']['city'];
			$line_1 = $company['addresses'][0]['address']['line_1'];

		}
		$data = array(
			"fullname"        => $company['name'],
			"telephone"        => $company['telephones'][0]['number'],
			"email"           => !is_null($company['emails'][0]['email']) ? $company['emails'][0]['email'] : '',
			"package"         => 0,
			"permission"      => "[]",
			"timezone"        => TIMEZONE,
			"expiration_date" => date('Y-m-d'),
			"status"          => 0,
			"role"          => 'customer',
			"changed"         => NOW,
			"avatar"         => '',
			"renouvler"         => true,
			"periode"         => 0,
			"rs"         => $company['name'],
			"id_team"         => '--',
			"new_id_teamlead"         => $company['id'],
		);
		
		$data_info = array(
			"id_user"        => "",
			"entreprise"     => !is_null($company['name']) ? $company['name'] : '--',
			"SIRET"          => !is_null($company['national_identification_number']) ? $company['national_identification_number'] : '--',
			"adresse"        => $line_1,
			"pays"           => 'France',
			"nom"            => '--',
			"prenom"         => '--',
			"website"        => !is_null($company['website']) ? $company['website'] : '--',
			"secteur"        => '--',
			"langue"         => !is_null($company['language']) ? $company['language'] : '--',
			"raison_social"  => !is_null($company['name']) ? $company['name'] : '--',
			"type_entreprise"  => '--',
			"n_tva"  => '--',
			"code_postal"  => $postal_code,
			"ville"  => $city,
		);
		
		$user = $this->model->get("*", $this->table, "email = '".$data['email']."'");
		$this->model->add_souscription($company['id']);
		if(!$user){
			$data["ids"]        = !is_null($company['id']) ? $company['id'] : ids();
			$data["login_type"] = "direct";
			$data["password"]   = md5('123456');
			$data["activation_key"] = $company['id'];
			$data["reset_key"]      = $company['id'];
			$data["created"]    = NOW;
			
			$this->db->insert('general_users', $data);
			
			$user = $this->model->get("*", $this->table, "ids = '{$company['id']}'");
			
			if($user) {
				$data_info["id_user"]      = $user->id;
				$this->db->insert('user_information', $data_info);
			}
			ms(array(
				"status"  => "sucess",
				"message" => lang("utilisateur ajouté avec succès")
			));
		}else{
			unset($data["id_team"]);
			unset($data["status"]);
			unset($data["ids"]);
			unset($data["package"]);
			unset($data["permission"]);
			unset($data["expiration_date"]);
			unset($data["avatar"]);
			unset($data["periode"]);
			unset($data["renouvler"]);
			$this->db->update($this->table, $data, array("ids" => $user->ids));
			unset($data_info["id_user"]);
			unset($data_info["n_tva"]);
			unset($data_info["type_entreprise"]);
			unset($data_info["secteur"]);
			if($role == 'customer') {
				$this->db->update('user_information', $data_info,array("id_user" => $user->id));
			}
			ms(array(
				"status"  => "sucess",
				"message" => lang("utilisateur modifié avec succès")
			));
		}	
		// dump($response);
		// $this->maj_user_from_teamleader($response);
	}
	public function add_user_cron(){
		set_time_limit(-1);
		
		$this->db->insert('crone_date', ["date_crone"=>NOW]);
		$customer_add=array();
		$customer_edit=array();
		$fullname="";
		$idT="";
		try{
			$acess = $this->model->refresh_token();
			$accessToken = get_option('teamleader_token');
			$invoices = array();
			$companiess = array();
			$page = 0;
			do {
				$page++;
				$test = false;
				$curl = curl_init();
				curl_setopt_array($curl, array(
					CURLOPT_URL => "https://api.teamleader.eu/companies.list",
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => "",
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "GET",
					CURLOPT_POSTFIELDS =>'{
						"filter": {
								"updated_since": "'.date("Y-m-d",strtotime("-15 day")).'T00:00:00+00:00"
						},
						"page": {"size": 99,"number": "'.$page.'"}
					}',
					CURLOPT_HTTPHEADER => array(
						"Authorization: Bearer ".$accessToken,
						"Content-Type: application/json"
					),
					
				));
				
				$response = curl_exec($curl);
				$response = json_decode($response, true);
				$companiess[] = $response['data'];
				if(isset($response['data'][0])){
					$test =true;
				}
			} while (!$test);
			curl_close($curl);
			foreach ($companiess as $key => $companies) {
				// var_dump($companies);
				# code...
				foreach ($companies as $key => $company) {
					# code...
					// echo "<pre>";
					// var_dump($company['name'],$company['id']);
					$data = array(
						"fullname"        => $company['name'],
						"telephone"        => $company['telephones'][0]['number'],
						"email"           => !is_null($company['emails'][0]['email']) ? $company['emails'][0]['email'] : '',
						"package"         => 0,
						"permission"      => "[]",
						"timezone"        => TIMEZONE,
						"expiration_date" => date('Y-m-d'),
						"status"          => 0,
						"role"          => 'customer',
						"changed"         => NOW,
						"avatar"         => '',
						"renouvler"         => true,
						"periode"         => 0,
						"rs"         => $company['name'],
						"id_team"         => '--',
						"new_id_teamlead"         => $company['id'],
					);
					$fullname=$company['name'];
					$idT=$company['id'];
					$postal_code = $company['primary_address']['postal_code'];
					$city = $company['primary_address']['city'];
					$line_1 = $company['primary_address']['line_1'];
					if($company['addresses']){
						$postal_code = $company['addresses'][0]['address']['postal_code'];
						$city = $company['addresses'][0]['address']['city'];
						$line_1 = $company['addresses'][0]['address']['line_1'];

					}
					$data_info = array(
						"id_user"        => "",
						"entreprise"     => !is_null($company['name']) ? $company['name'] : '--',
						"SIRET"          => !is_null($company['national_identification_number']) ? $company['national_identification_number'] : '--',
						"adresse"        => $line_1,
						"pays"           => 'France',
						"nom"            => '--',
						"prenom"         => '--',
						"website"        => !is_null($company['website']) ? $company['website'] : '--',
						"secteur"        => '--',
						"langue"         => !is_null($company['language']) ? $company['language'] : '--',
						"raison_social"  => !is_null($company['name']) ? $company['name'] : '--',
						"type_entreprise"  => '--',
						"n_tva"  => !is_null($company['vat_number']) ? $company['vat_number'] : '--',
						"code_postal"  => $postal_code,
						"ville"  => $city,
					);
					
					$user = $this->model->get("*", $this->table, "id_team = '".$data['id_team']."'");
					$sousc = $this->model->add_souscription($company['id']);
					if($sousc['result']){
						$data["first_date"] = date("Y-m-d H:i:s", strtotime($sousc['date']));
						if(!$user){
							$data["ids"]        = !is_null($company['id']) ? $company['id'] : ids();
							$data["login_type"] = "direct";
							$data["password"]   = md5('123456');
							$data["activation_key"] = $company['id'];
							$data["reset_key"]      = $company['id'];
							$data["created"]    = NOW;
							$this->db->insert('general_users', $data);
							
							$user = $this->model->get("*", $this->table, "ids = '{$company['id']}'");
							
							if($user) {
								$data_info["id_user"]      = $user->id;
								$this->db->insert('user_information', $data_info);
							}
							array_push($customer_add, array($data["fullname"],$company["id"]));

						}else{
							
							unset($data["id_team"]);
							unset($data["status"]);
							unset($data["ids"]);
							unset($data["package"]);
							unset($data["permission"]);
							unset($data["avatar"]);
							unset($data["periode"]);
							unset($data["renouvler"]);
							$this->db->update($this->table, $data, array("ids" => $user->ids));
							unset($data_info["id_user"]);
							unset($data_info["type_entreprise"]);
							unset($data_info["secteur"]);
							$this->db->update('user_information', $data_info,array("id_user" => $user->id));
							array_push($customer_edit, array($data["fullname"],$company["id"]));
							
						}	
					}
				}
			}
			$html="Crone Effectue à ".date("d-m-Y H:m:s")."<br>";
			if(empty($customer_add)){
				$html.="Aucun Utilisateur Ajouter.<br/>";
			}else{
				$html.="Liste Utilisateur Ajouter.<br/>";
				foreach($customer_add as $customer){
					$html.="Full Name : ".$customer[0]." IdTeamlead : ".$customer[1]."<br/>";
				}
			}
			if(empty($customer_edit)){
				$html.="Aucun Utilisateur Modifier.<br/>";
			}else{
				$html.="Liste Utilisateur Modifier.<br/>";
				foreach($customer_edit as $customer){
					$html.="Full Name : ".$customer[0]." IdTeamlead : ".$customer[1]."<br/>";
				}
			}
			$this->model->send_email_crone($html);
			$croneLogFile = fopen("log.txt", "a") or die("Unable to open file!");
			fwrite($croneLogFile,$html. "\t" . date("Ymd-His") . "\n--------------------------------------------------------\n\n");
			fclose($croneLogFile);
		}catch(Exception $e) {
			$html="Crone Effectue à ".date("d-m-Y H:m:s")."<br>";
			if(empty($customer_add)){
				$html.="Aucun Utilisateur Ajouter.<br/>";
			}else{
				$html.="Liste Utilisateur Ajouter.<br/>";
				foreach($customer_add as $customer){
					$html.="Full Name : ".$customer[0]." IdTeamlead : ".$customer[1]."<br/>";
				}
			}
			if(empty($customer_edit)){
				$html.="Aucun Utilisateur Modifier.<br/>";
			}else{
				$html.="Liste Utilisateur Modifier.<br/>";
				foreach($customer_edit as $customer){
					$html.="Full Name : ".$customer[0]." IdTeamlead : ".$customer[1]."<br/>";
				}
			}
			$html.="Error de traite client : ";
			$html.="Full Name : ".$fullname." IdTeamlead : ".$idT."<br/>";
			$html.='Exception reçue : '.  $e->getMessage(). "\n";
			$this->model->send_email_crone($html);
		}
		
		ms(array(
			"status"  => "success",
			"message" => lang("utilisateurs ajouté avec succès")
		));
		// dump($response);
		// $this->maj_user_from_teamleader($response);
	}
	public function add_user_cron_date(){
		$data = array(
			"module"      => get_class($this),
			"module_name" => $this->module_name,
			"module_icon" => $this->module_icon,
		);
		$this->template->build('create_api_date', $data);
	}
	public function add_user_cron_by_date(){
		$acess = $this->model->refresh_token();
		$accessToken = get_option('teamleader_token');
		$invoices = array();
		$companiess = array();
		$page = 0;
		$date=post("date");
		do {
			$page++;
			$test = false;
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://api.teamleader.eu/companies.list",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_POSTFIELDS =>'{
					"filter": {
							"updated_since": "'.$date.'T00:00:00+00:00"
					},
					"page": {"size": 99,"number": "'.$page.'"}
				}',
				CURLOPT_HTTPHEADER => array(
					"Authorization: Bearer ".$accessToken,
					"Content-Type: application/json"
				),
				
			));
			
			$response = curl_exec($curl);
			$response = json_decode($response, true);
			$companiess[] = $response['data'];
			if(isset($response['data'][0])){
				$test =true;
			}
		} while (!$test);
		curl_close($curl);
		foreach ($companiess as $key => $companies) {
			// var_dump($companies);
			# code...
			foreach ($companies as $key => $company) {
				# code...
				// echo "<pre>";
				// var_dump($company['name'],$company['id']);
				$data = array(
					"fullname"        => $company['name'],
					"telephone"        => $company['telephones'][0]['number'],
					"email"           => !is_null($company['emails'][0]['email']) ? $company['emails'][0]['email'] : '',
					"package"         => 0,
					"permission"      => "[]",
					"timezone"        => TIMEZONE,
					"expiration_date" => date('Y-m-d'),
					"status"          => 0,
					"role"          => 'customer',
					"changed"         => NOW,
					"avatar"         => '',
					"renouvler"         => true,
					"periode"         => 0,
					"rs"         => $company['name'],
					"id_team"         => '--',
					"new_id_teamlead"         => $company['id'],
				);
				$postal_code = $company['primary_address']['postal_code'];
				$city = $company['primary_address']['city'];
				$line_1 = $company['primary_address']['line_1'];
				if($company['addresses']){
					$postal_code = $company['addresses'][0]['address']['postal_code'];
					$city = $company['addresses'][0]['address']['city'];
					$line_1 = $company['addresses'][0]['address']['line_1'];

				}
				$data_info = array(
					"id_user"        => "",
					"entreprise"     => !is_null($company['name']) ? $company['name'] : '--',
					"SIRET"          => !is_null($company['national_identification_number']) ? $company['national_identification_number'] : '--',
					"adresse"        => $line_1,
					"pays"           => 'France',
					"nom"            => '--',
					"prenom"         => '--',
					"website"        => !is_null($company['website']) ? $company['website'] : '--',
					"secteur"        => '--',
					"langue"         => !is_null($company['language']) ? $company['language'] : '--',
					"raison_social"  => !is_null($company['name']) ? $company['name'] : '--',
					"type_entreprise"  => '--',
					"n_tva"  => !is_null($company['vat_number']) ? $company['vat_number'] : '--',
					"code_postal"  => $postal_code,
					"ville"  => $city,
				);
				
				$user = $this->model->get("*", $this->table, "email = '".$data['email']."'");
				$sousc = $this->model->add_souscription($company['id']);
				if($sousc['result']){
					$data["first_date"] = date("Y-m-d H:i:s", strtotime($sousc['date']));
					if(!$user){
						$data["ids"]        = !is_null($company['id']) ? $company['id'] : ids();
						$data["login_type"] = "direct";
						$data["password"]   = md5('123456');
						$data["activation_key"] = $company['id'];
						$data["reset_key"]      = $company['id'];
						$data["created"]    = NOW;
						
						$this->db->insert('general_users', $data);
						
						$user = $this->model->get("*", $this->table, "ids = '{$company['id']}'");
						
						if($user) {
							$data_info["id_user"]      = $user->id;
							$this->db->insert('user_information', $data_info);
						}
					}else{
						
						unset($data["id_team"]);
						unset($data["status"]);
						unset($data["ids"]);
						unset($data["package"]);
						unset($data["permission"]);
						unset($data["avatar"]);
						unset($data["periode"]);
						unset($data["renouvler"]);
						$this->db->update($this->table, $data, array("ids" => $user->ids));
						unset($data_info["id_user"]);
						unset($data_info["type_entreprise"]);
						unset($data_info["secteur"]);
						$this->db->update('user_information', $data_info,array("id_user" => $user->id));
						
					}	
				}
			}
		}
		ms(array(
			"status"  => "success",
			"message" => lang("utilisateurs ajouté avec succès")
		));
		// dump($response);
		// $this->maj_user_from_teamleader($response);
	}
	public function update_user_id_team(){





		$acess = $this->model->refresh_token();
		$accessToken = get_option('teamleader_token');
		$invoices = array();
		$limit = 100000;
		$page  =0;
		$users = $this->model->fetch("*",$this->table);
		// dump($users);
		foreach ($users as $key => $user) {
			# code...
			$id_team = $user->new_id_teamlead;
			if(!$id_team){
				$id_team = $user->id_team;
			}
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://api.teamleader.eu/companies.info?id=".$id_team,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_HTTPHEADER => array(
					"Authorization: Bearer ".$accessToken,
					"Content-Type: application/json"
				),
				
			));
			
			$response = curl_exec($curl);
			$response = json_decode($response, true);
			curl_close($curl);
			if(isset($response['data'])){
				
				$company = $response['data'];
				$data = array(
					"fullname"        => $company['name'],
					"telephone"        => $company['telephones'][0]['number'],
					"email"           => !is_null($company['emails'][0]['email']) ? $company['emails'][0]['email'] : '',
					"package"         => 0,
					"permission"      => "[]",
					"timezone"        => TIMEZONE,
					"expiration_date" => date('Y-m-d'),
					"status"          => 0,
					"role"          => 'customer',
					"changed"         => NOW,
					"avatar"         => '',
					"renouvler"         => true,
					"periode"         => 12,
					"rs"         => $company['name'],
					"id_team"         => "--",
					"new_id_teamlead"         => $company['id'],
				);
				// get data from orders by ids 
				$teamleader_api_group = get_option('teamleader_api_group');
				$teamleader_api_secret = get_option('teamleader_api_secret');
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'https://app.teamleader.eu/api/getInvoices.php');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, [
					'date_from' => '01/01/2015',
					'api_group' => $teamleader_api_group,
					'api_secret' => $teamleader_api_secret,
					'date_to' => date('d/m/Y'),
					'contact_or_company' => 'company',
					'contact_or_company_id' => $user->id_teamlead_new,
				]);
				$response_inv = curl_exec($ch);
				$invoices = json_decode($response_inv, true);
				$invoices_end=end($invoices);
				$date=$invoices_end["date_formatted"];
				
				try{
					list($day,$mount,$year) = explode('/', $date);
					if($user->periode==0){
						$user->periode=12;
					}
					$first_date = $year.'-'.$mount.'-'.$day;
					$updatedate = new DateTime($first_date);
					$dif_suspension=$user->periode+$user->suspension;
					
					$updatedate->add(new DateInterval('P'.$dif_suspension.'M'));
					$updatedate = $updatedate->format('Y-m-d');
					$data["expiration_date"]=$updatedate;
					$data["first_date"]=$first_date;
				}catch (Exception $e) {

				}
				
				
				$postal_code = $company['primary_address']['postal_code'];
				$city = $company['primary_address']['city'];
				$line_1 = $company['primary_address']['line_1'];
				if($company['addresses']){
					$postal_code = $company['addresses'][0]['address']['postal_code'];
					$city = $company['addresses'][0]['address']['city'];
					$line_1 = $company['addresses'][0]['address']['line_1'];
	
				}
				$data_info = array(
					"id_user"        => "",
					"entreprise"     => !is_null($company['name']) ? $company['name'] : '--',
					"SIRET"          => !is_null($company['national_identification_number']) ? $company['national_identification_number'] : '--',
					"adresse"        => $line_1,
					"pays"           => 'France',
					"nom"            => '--',
					"prenom"         => '--',
					"website"        => !is_null($company['website']) ? $company['website'] : '--',
					"secteur"        => '--',
					"langue"         => !is_null($company['language']) ? $company['language'] : '--',
					"raison_social"  => !is_null($company['name']) ? $company['name'] : '--',
					"type_entreprise"  => '--',
					"n_tva"  => !is_null($company['vat_number']) ? $company['vat_number'] : '--',
					"code_postal"  => $postal_code,
					"ville"  => $city,
				);
				$user = $this->model->get("*", $this->table, "email = '".$data['email']."'");
				// unset($data["expiration_date"]);
				unset($data["id_team"]);
				unset($data["status"]);
				unset($data["ids"]);
				unset($data["package"]);
				unset($data["permission"]);
				unset($data["avatar"]);
				unset($data["periode"]);
				unset($data["renouvler"]);
				$this->db->update($this->table, $data, array("ids" => $user->ids));
				$user_info = $this->model->get("*", 'user_information', "id_user = '".$user->id."'");
				if($user_info){
					unset($data_info["id_user"]);
					unset($data_info["type_entreprise"]);
					unset($data_info["secteur"]);
					$ret = $this->db->update('user_information', $data_info,array("id_user" => $user->id));
				}else{
					$data_info["id_user"]      = $user->id;
					$this->db->insert('user_information', $data_info);
				}
			}
			
		}
		// die();
			
				
		// dump($response);
		// $this->maj_user_from_teamleader($response);
	}

	public function user_invoice_api(){
		$company_id = !empty(get('company_id')) ? get('company_id') : 0;
		$accessToken = get_option('teamleader_token');
		$invoices = array();
		$redirectUri = cn().'users/user_api';
		$pos = strrpos(cn(), ".local");
		if($pos > 0) {//Local on utilise json stocker dans option
			return false;
		} else {
			if (!empty($accessToken)) {
			    
			    /**
			     * Get the user identity information using the access token.
			     */
			    $ch = curl_init();
			    curl_setopt($ch, CURLOPT_URL, 'https://app.teamleader.eu/api/getInvoices.php');
			    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			    curl_setopt($ch, CURLOPT_POSTFIELDS, [
			        'date_from' => '01/01/2015',
			        'date_to' => date('d/m/Y'),
			        'contact_or_company' => 'company',
			        'contact_or_company_id' => (int)$company_id,
			    ]);
			    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $accessToken]);

			    $response = curl_exec($ch);
			    $data['invoices'] = json_decode($response, true);
			    $this->template->build('invoice_user', $data);
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

	public function maj_user_from_teamleader($companies = null){
		dump($companies);
		if($companies == null){
			$companies = json_decode(get_option('company_json'), true);
		}
		foreach ( $companies['data'] as $key => $company) {
			$data = array(
				"fullname"        => $company['name'],
				"telephone"        => $company['telephones'][0]['number'],
				"email"           => !is_null($company['emails'][0]['email']) ? $company['emails'][0]['email'] : '',
				"package"         => 0,
				"permission"      => "[]",
				"timezone"        => TIMEZONE,
				"expiration_date" => date('Y-m-d'),
				"status"          => 0,
				"role"          => 'customer',
				"changed"         => NOW,
				"avatar"         => '',
				"renouvler"         => true,
				"periode"         => 0,
				"rs"         => $company['name'],
				"id_team"         => $company['id'],
			);

			$data_info = array(
				"id_user"        => "",
				"entreprise"     => !is_null($company['name']) ? $company['name'] : '--',
				"SIRET"          => !is_null($company['vat_number']) ? $company['vat_number'] : '--',
				"adresse"        => $company['addresses'][0]['address']['line_1'],
				"pays"           => 'France',
				"nom"            => '--',
				"prenom"         => '--',
				"website"        => !is_null($company['website']) ? $company['website'] : '--',
				"secteur"        => '--',
				"langue"         => !is_null($company['language']) ? $company['language'] : '--',
				"raison_social"  => !is_null($company['name']) ? $company['name'] : '--',
				"type_entreprise"  => '--',
				"n_tva"  => '--',
				"code_postal"  => !is_null($company['addresses'][0]['address']['postal_code']) ? $company['addresses'][0]['address']['postal_code'] : '--',
				"ville"  => !is_null($company['addresses'][0]['address']['city']) ? $company['addresses'][0]['address']['city'] : '--',
			);

			$data["ids"]        = !is_null($company['id']) ? $company['id'] : ids();
			$data["login_type"] = "direct";
			$data["password"]   = md5('123456');
			$data["activation_key"] = $company['id'];
			$data["reset_key"]      = $company['id'];
			$data["created"]    = NOW;

			$this->db->delete('general_users', array("ids " => $company['id']));
			$this->db->insert('general_users', $data);

			$user = $this->model->get("*", $this->table, "ids = '{$company['id']}'");

			if($user) {
				$data_info["id_user"]      = $user->id;
				$this->db->delete('user_information', array("id_user " => $user->id));
				$this->db->insert('user_information', $data_info);
			}
		}
		echo "Fin MAJ";exit;
	}



	public function update(){
		$redirect = 'users';
		if(isset($_GET['role']) && $_GET['role'] == 'customer') {
			$redirect = 'users/customer';
		} elseif(isset($_GET['role']) && $_GET['role'] == 'manager') {
			$redirect = 'users/manager';
		} elseif(isset($_GET['role']) && $_GET['role'] == 'responsable') {
			$redirect = 'users/res_cm';
		} elseif(isset($_GET['role']) && $_GET['role'] == 'admin') {
			$redirect = 'users/admin';
		}
		$create_customer = (isset($_GET['role']) && $_GET['role'] == 'customer') ? true : false;
		$groupes = $this->model->fetch(
			GROUPES . ".ids,".GROUPES.".name,".USERS.".fullname ", 
			array(GROUPES, USERS,"manager_group"), 
			GROUPES . '.ids = manager_group.id_group and manager_group.id_user='.USERS.".ids and " . USERS . ".status=1"
		);
		$user = $this->model->get("id,ids", USERS, "id = ".segment(3));
		$managers=$this->model->fetch("*",USERS,"role='manager' and status=1");
		$manager_user=$this->model->get("*","customer_cm","id_customer=".$user->id);
		$data = array(
			"user"=>$user,
			"managers"=>$managers,
			"manager_user"=>$manager_user,
			"packages"    => $this->model->fetch("name, id, ids", PACKAGES),
			"groupes"    => $this->model->fetch("name, id, ids", GROUPES),
			"result"      => $this->model->getUSer($user->ids),       
			"module"      => get_class($this),
			"module_name" => $this->module_name,
			"module_icon" => $this->module_icon,
			"create_customer" => $create_customer,
			"redirect" => $redirect
		);
		$this->template->build('update', $data);
	}
	public function index() {
		return $this->customer();
	}

	public function customer() {
		$page        = (int)get("p");
		$limit       = 1000;
		$result=null;
		if(session("uid")){
			if(is_admin()) {
				$result      = $this->model->getList($this->table, $this->columns, $limit, $page,'customer');
			
			}else if(is_responsable()) {
				$result = getUsersByRole($from='customer');	
			}else if(is_manager()) {
				$result = getUsersByRole($from='customer');
			} else {
				$result      = $this->model->getList($this->table, $this->columns, $limit, $page,'customer');
			}
		}else{
			$result      = $this->model->getList($this->table, $this->columns, $limit, $page,'customer');
		}
		$total       = $this->model->getList($this->table, $this->columns, -1, -1);
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
			"base_url"   => cn(get_class($this).$query_string), 
			"total_rows" => $total_final, 
			"per_page"   => $limit
		);

		$this->pagination->initialize($configs);
		$data = array(
			"columns" => $this->columns,
			"users"  => $result,
			"total"   => $total_final,
			"page"    => $page,
			"limit"   => $limit,
			"module"  => get_class($this),
			"module_name" => $this->module_name,
			"module_icon" => $this->module_icon,
			"user_active"=>"1"
		);
		$this->template->build('customer', $data);
	}
	public function customer_pg() {
		$page        = (int)get("p");
		$limit       = 10;
		$result      = $this->model->getList($this->table, $this->columns, $limit, $page,'customer');
		$total       = $this->model->getList($this->table, $this->columns, -1, -1);
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
			"base_url"   => cn(get_class($this).'/customer_pg'.$query_string), 
			"total_rows" => $total_final, 
			"per_page"   => $limit
		);

		$this->pagination->initialize($configs);

		$data = array(
			"columns" => $this->columns,
			"users"  => $result,
			"total"   => $total_final,
			"page"    => $page,
			"limit"   => $limit,
			"module"  => get_class($this),
			"module_name" => $this->module_name,
			"module_icon" => $this->module_icon
		);
		$this->template->build('customer_pg', $data);
	}
	public function customer_inactif() {
		$page        = (int)get("p");
		$limit       = 1000;
		$result      = $this->model->getList($this->table, $this->columns, $limit, $page,'customer_inactif');
		$total       = $this->model->getList($this->table, $this->columns, -1, -1);
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
			"base_url"   => cn(get_class($this).$query_string), 
			"total_rows" => $total_final, 
			"per_page"   => $limit
		);

		$this->pagination->initialize($configs);

		$data = array(
			"columns" => $this->columns,
			"users"  => $result,
			"total"   => $total_final,
			"page"    => $page,
			"limit"   => $limit,
			"module"  => get_class($this),
			"module_name" => $this->module_name,
			"module_icon" => $this->module_icon,
			"user_active"=>0
		);
		$this->template->build('customer', $data);
	}

	public function manager() {
		$page        = (int)get("p");
		$limit       = 5600;
		// $result      = $this->model->getList($this->table, $this->columns, $limit, $page, 'manager');
		$total       = $this->model->getList($this->table, $this->columns, -1, -1);
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
			"base_url"   => cn(get_class($this).$query_string), 
			"total_rows" => $total_final, 
			"per_page"   => $limit
		);
		// var_dump($this->model->get_user_res_manger());die();
		$this->pagination->initialize($configs);

		$data = array(
			"columns" => $this->columns,
			"users"  => $this->model->get_user_res_manger(),
			"total"   => $total_final,
			"page"    => $page,
			"limit"   => $limit,
			"module"  => get_class($this),
			"module_name" => $this->module_name,
			"module_icon" => $this->module_icon
		);
		if(is_admin() || is_responsable()){
			$this->template->build('manager', $data);
		}else{
			redirect(cn('users/customer'));
		}
	}

	public function admin() {
		$page        = (int)get("p");
		$limit       = 5600;
		$result      = $this->model->getList($this->table, $this->columns, $limit, $page, 'admin');
		$total       = $this->model->getList($this->table, $this->columns, -1, -1);
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
			"base_url"   => cn(get_class($this).$query_string), 
			"total_rows" => $total_final, 
			"per_page"   => $limit
		);

		$this->pagination->initialize($configs);

		$data = array(
			"columns" => $this->columns,
			"users"  => $result,
			"total"   => $total_final,
			"page"    => $page,
			"limit"   => $limit,
			"module"  => get_class($this),
			"module_name" => $this->module_name,
			"module_icon" => $this->module_icon
		);
		if(is_admin()){
			$this->template->build('admin', $data);
		}else{
			redirect(cn('users/customer'));
		}
	}

	public function res_cm() {
		$page        = (int)get("p");
		$limit       = 5600;
		$result      = $this->model->getList($this->table, $this->columns, $limit, $page, 'responsable');
		$total       = $this->model->getList($this->table, $this->columns, -1, -1);
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
			"base_url"   => cn(get_class($this).$query_string), 
			"total_rows" => $total_final, 
			"per_page"   => $limit
		);

		$this->pagination->initialize($configs);

		$data = array(
			"columns" => $this->columns,
			"users"  => $result,
			"total"   => $total_final,
			"page"    => $page,
			"limit"   => $limit,
			"module"  => get_class($this),
			"module_name" => $this->module_name,
			"module_icon" => $this->module_icon
		);
		if(is_admin()){
			$this->template->build('responsable', $data);
		}else{
			redirect(cn('users/customer'));
		}
	}

	public function ajax_update(){
		
		$ids      = post("ids");
		$status      = post("status");
		$role      = post("role");
		$renouvler      = post("renouvler");
		$phone      = post("phone");
		$select_periode  = post("select_periode");
		$email    = post("email");
		$password = post("password");
		$package_ids  = post("package");
		$confirm_password = post("confirm_password");
		$expiration_date  = post("expiration_date");
		$first_date  = post("first_date");
		$groupes  = post("groupes");
		$timezone  = post("timezone");
		$type_entreprise  = post("entreprise");
		$n_tva  = post("n_tva");
		$SIRET  = post("SIRET");
		$adresse  = post("adresse");
		$adresse2  = post("adresse2");
		$code_postal  = post("code_postal");
		$ville  = post("ville");
		$pays  = post("pays");
		$prenom  = post("prenom");
		$nom  = post("nom");
		$fullname  = post("fullname");
		$website  = post("website");
		$secteur  = post("secteur");
		$id_teamlead  = post("id_teamlead");
		$create_a_post  = post("create_a_post");
		$raison_social  = post("raison_social");		
		$media  = (post("media") != null) ? post("media")[0] : '';
		$langue  = get_default_language();
		$reduction  = (int)post("reduction");
		$reduction_value  = (int)post("reduction_value");
		$suspension  = (int)post("suspension");
		$id_teamlead_new=post("id_teamlead_new");
		if(!empty($prenom) || !empty($nom)) {
			$fullname = $prenom." ".$nom;
		}
		
		$create_customer = (isset($_GET['role']) && $_GET['role'] == 'customer') ? true : false;
		if($fullname == ""){
			ms(array(
				"status"  => "error",
				"message" => lang("please_enter_fullname")
			));
		}
		
		if($email == ""){
			ms(array(
				"status"  => "error",
				"message" => lang("please_enter_email")
			));
		}

		if(!filter_var(post("email"), FILTER_VALIDATE_EMAIL)){
		  	ms(array(
				"status"  => "error",
				"message" => lang("email_address_in_invalid_format")
			));
		}
		if($first_date == ""  && $role == 'customer'){
			ms(array(
				"status"  => "error",
				"message" => lang("please_select_date")
			));
		}
		if($package_ids == "" && $role == 'customer'){
			ms(array(
				"status"  => "error",
				"message" => lang("please_select_a_package")
			));
		}
		
		$updatedate = $expiration_date;
		if($role == 'customer') {
			list($day,$mount,$year) = explode('/', $first_date);
			if((int)$day >= 15) {
				$per = $select_periode;
			} else {
				$per = $select_periode-1;
			}
			
			$first_date = $year.'-'.$mount.'-'.$day;
			$date = new DateTime($first_date);
			$date->add(new DateInterval('P'.$per.'M')); //Où 'P12M' indique 'Période de 12 Mois'
			$expiration_date = $date->format('Y-m-d');

		} else {
			$expiration_date = '2022-01-31';
			$renouvler = 1;
			$select_periode = 12;
			$first_date = date('Y-m-d');
		}

		if($expiration_date == "" && $role == 'customer'){
			ms(array(
				"status"  => "error",
				"message" => lang("please_enter_expiration_date")
			));
		}

		$package = $this->model->get('*', PACKAGES, "ids = '$package_ids'");
		if(empty($package) && $role == 'customer'){
			ms(array(
				"status"  => "error",
				"message" => lang('package_does_not_exist')
			));
		}

		$check_timezone = 0;
		foreach (tz_list() as $value) {
			if($timezone == $value['zone']){
				$check_timezone = 1;
			}
		}
		if(!$check_timezone){
			$timezone = TIMEZONE;
			// ms(array(
			// 	"status"  => "error",
			// 	"message" => lang('timezone_is_required')
			// ));
		}
		$pack_permission = array();
		$packageid="";
		$packagepermission = "[]";

		if(!empty($package)){
			
			$pack_permission = (array)json_decode($package->permission);
			if(post("create_a_post")){
				$pack_permission["create_a_post"]= $create_a_post;
			}
			$package->permission = json_encode($pack_permission, 0);
			$packagepermission = $package->permission;
			// var_dump($package);
			$packageid = $package->id;
			// var_dump($packageid);die;

		}
		if($role=="manager"){
			$packagepermission='{"0":"facebook_enable","1":"facebook\/post","2":"instagram_enable","3":"instagram\/post","4":"twitter_enable","5":"twitter\/post","6":"google_drive","7":"dropbox","8":"photo_type","9":"video_type","max_storage_size":20000,"max_file_size":10000,"watermark":"watermark","image_editor":"image_editor","create_a_post":null}';
		}elseif($role=="responsable"){
			$packagepermission='{"0":"facebook_enable","1":"facebook\/post","2":"instagram_enable","3":"instagram\/post","4":"twitter_enable","5":"twitter\/post","6":"google_drive","7":"dropbox","8":"photo_type","9":"video_type","max_storage_size":20000,"max_file_size":10000,"watermark":"watermark","image_editor":"image_editor","create_a_post":null}';
		}
		// echo "<pre>";var_dump($package,$packagepermission);die();

		
		//
		$data = array(
			"fullname"        => $fullname,
			"telephone"        => $phone,
			"email"           => $email,
			"package"         => $packageid,
			"permission"      => $packagepermission,
			"timezone"        => $timezone,
			"expiration_date" => $expiration_date,
			"first_date" => $first_date,
			"status"          => $status,
			"role"          => $role,
			"changed"         => NOW,
			"avatar"         => $media,
			"renouvler"         => $renouvler,
			"rs"         => !empty($raison_social) ? $raison_social : $fullname,
			"periode"         => $select_periode,
			"reduction" => $reduction,
			"reduction_value" => $reduction_value,
			"id_teamlead_new" => $id_teamlead_new
		);
		$data_info = array(
			"id_user"        => "",
			"entreprise"     => $raison_social,
			"SIRET"          => $SIRET,
			"adresse"        => $adresse,
			"pays"           => $pays,
			"nom"            => $nom,
			"prenom"         => $prenom,
			"website"        => $website,
			"secteur"        => $secteur,
			"langue"         => $langue->code,
			"raison_social"  => $raison_social,
			"type_entreprise"  => $type_entreprise,
			"raison_social"  => $raison_social,
			"n_tva"  => $n_tva,
			"code_postal"  => $code_postal,
			"ville"  => $ville,
		);

		$user = $this->model->get("*", $this->table, "ids = '{$ids}'");
		
		$role = post('role');
		if($role == 'customer') {
			if($id_teamlead == ""){
				ms(array(
					"status"  => "error",
					"message" => lang("Id est vide")
				));
			}
			if(empty($raison_social)){
				ms(array(
					"status"  => "error",
					"message" => lang("raison_social_required")
				));
			}
			if(empty($SIRET)){
				ms(array(
					"status"  => "error",
					"message" => lang("siret_required")
				));
			}
			if(empty($type_entreprise)){
				ms(array(
					"status"  => "error",
					"message" => lang("type_entreprise_required")
				));
			}

			if(empty($n_tva)){
				ms(array(
					"status"  => "error",
					"message" => lang("n_tva_required")
				));
			}
			if(empty($adresse)){
				ms(array(
					"status"  => "error",
					"message" => lang("adresse_required")
				));
			}

			if(empty($code_postal)){
				ms(array(
					"status"  => "error",
					"message" => lang("code_postal_required")
				));
			}

			if(empty($ville)){
				ms(array(
					"status"  => "error",
					"message" => lang("ville_required")
				));
			}

			if(empty($pays)){
				ms(array(
					"status"  => "error",
					"message" => lang("pays_required")
				));
			}

			
		}
			
		if(empty($user)){
			if($password == ""){
				ms(array(
					"status"  => "error",
					"message" => lang("please_enter_password")
				));
			}


			if(strlen($password) <= 5){
				ms(array(
					"status"  => "error",
					"message" => lang("password_must_be_greater_than_5_characters")
				));
			}

			if($password != $confirm_password){
				ms(array(
					"status"  => "error",
					"message" => lang("password_does_not_match_the_confirm_password")
				));
			}

			//
			$user_check = $this->model->get("id", $this->table, "email = '{$email}'");
			if(!empty($user_check)){
				ms(array(
					"status"  => "error",
					"message" => lang("this_email_already_exists")
				));
			}

			$data["ids"]        = ids();
			$data["login_type"] = "direct";
			$data["password"]   = md5($password);
			$data["activation_key"] = ids();
			$data["reset_key"]      = ids();
			$data["status"]      = post("status");

			$data["created"]    = NOW;
			$data["id_teamlead_new"]="";
			// if(!is_null($groupes)) {
			// 	$this->model->insertGroupes($groupes, $user->ids);
			// }
			$rest=$this->db->insert($this->table, $data);
			
			$insert_id = $this->db->insert_id();

			// var_dump($insert_id,$this->table,$data,$rest);die();
			$data_info['id_user'] = $insert_id;
			if($role == 'customer') {
				$this->db->insert('user_information', $data_info);
				if(post("community_manager")!=""){
					
					$data_customer_cm=array();
					$data_customer_cm["id_customer"]=$insert_id;
					$data_customer_cm["id_manager"]=post("community_manager");
					$this->db->insert('customer_cm', $data_customer_cm);
				}
			}
		}else{
			if($password != ""){
				if(strlen($password) <= 5){
					ms(array(
						"status"  => "error",
						"message" => lang("password_must_be_greater_than_5_characters")
					));
				}

				if($password != $confirm_password){
					ms(array(
						"status"  => "error",
						"message" => lang("password_does_not_match_the_confirm_password")
					));
				}

				$data["password"] = md5($password);
			}

			//
			$user_check = $this->model->get("id", $this->table, "email = '{$email}' AND email != '{$user->email}'");
			if(!empty($user_check)){
				ms(array(
					"status"  => "error",
					"message" => lang("this_email_already_exists")
				));
			}
			
			if($id_teamlead){
				$data["ids"] = $id_teamlead;
			}
			if($role == 'customer') {
				list($day,$mount,$year) = explode('/', $first_date);
				
				$updatedate = $year.'-'.$mount.'-'.$day;
				$updatedate = new DateTime($first_date);
				// if($suspension != 0 && $suspension != $user->suspension){
				$dif_suspension=$select_periode+$suspension;
				// $u_expiration_date=date('Y-m-d', strtotime("+".$dif_suspension." months", strtotime($expiration_date)));
				
				$updatedate->add(new DateInterval('P'.$dif_suspension.'M'));
				$updatedate = $updatedate->format('Y-m-d');
				$data['expiration_date'] = $updatedate;

				// }
				$data['suspension'] = $suspension;
				// var_dump($suspension,$suspension_old,$dif_suspension,$u_expiration_date);die();
			}
			$this->model->insertGroupes($groupes, $user->ids);
			// Send Mail
			// var_dump($user->status,post("status"));	
			if($user->status=='0' && post("status")=='1'){
				$alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.
				'0123456789`-=~!@#$%^&*()_+,./<>?;:[]{}\|';
				$pass =""; 
				$alphaLength = strlen($alphabet) - 1; 
				for ($i = 0; $i < 12; $i++) {
					$n = rand(0, $alphaLength);
					$pass.= $alphabet[$n];
				}
				$password= $pass;
				$data["password"] = md5($password);
				$html="";
				// var_dump("Idinfluenceur : Information de votre compte", "Votre email :".$user->email."\nVotre mot de passe :".$password."<a href='https://espaceclient.idinfluencer.com/profile'>Accéder à mon profile</a>",$user->id);
				// $res=$this->model->send_email("Idinfluenceur : Information de votre compte", "Votre email :".$user->email."\nVotre mot de passe :".$password."<a href='https://espaceclient.idinfluencer.com/profile'>Accéder à mon profile</a>", $user->id);
					// "Idinfluenceur : Information de votre compte", "Votre email :".$user->email."\nVotre mot de passe :".$password."<br/><a href='https://espaceclient.idinfluencer.com/profile'>Accéder à mon profile</a>"
					$res=$this->model->send_email_activation("Votre compte Id'Influencer : ".$user->rs,$user->email,$password, $user->id);
				// var_dump($res);
			}
			
			// die();

			$this->db->update($this->table, $data, array("ids" => $user->ids));
			unset($data_info["id_user"]);
			if($role == 'customer') {
				$this->db->update('user_information', $data_info,array("id_user" => $user->id));
				if(post("community_manager")!=""){
					// die("here hatim");
					$this->db->select("*");
					$this->db->from("customer_cm");
					$this->db->where("id_customer = ".$user->id);
					$users=$this->db->get()->result();
					$data_customer_cm=array();
					$data_customer_cm["id_customer"]=$user->id;
					$data_customer_cm["id_manager"]=post("community_manager");
					if($users==null){
						$this->db->insert('customer_cm', $data_customer_cm);

					}else{
						$this->db->update('customer_cm', $data_customer_cm,array("id_customer" => $user->id));
					}
				}
			}
		}
		ms(array(
			"status"  => "success",
			"message" => lang("successfully")
		));
	}

	public function export(){
		export_csv($this->table);
	}

	public function ajax_update_status(){
		$this->model->update_status($this->table, post("id"), false);
	}
	
	public function ajax_delete_item(){
		$this->model->delete($this->table, post("id"), false);
		ms(array(
			"status"  => "success",
			"message" => lang("successfully")
		));
	}

	public function import_user() {
		for ($i=3; $i <= 33 ; $i++) { 
			$companies = json_decode(get_option("companies_list_{$i}"), true);
			foreach ($companies as $key => $company) {

				$data = array(
					"fullname"        => $company['name'],
					"telephone"        => $company['telephones'],
					"email"           => $company['email'],
					"package"         => 0,
					"permission"      => "[]",
					"timezone"        => TIMEZONE,
					"expiration_date" => date('Y-m-d'),
					"status"          => 0,
					"role"          => 'customer',
					"changed"         => NOW,
					"avatar"         => '',
					"renouvler"         => true,
					"periode"         => 0,
					"rs"         => $company['name'],
					"id_team"         => $company['id'],
				);

				$data_info = array(
					"id_user"        => "",
					"entreprise"     => $company['name'],
					"SIRET"          => $company['taxcode'],
					"adresse"        => $company['street'].', '.$company['street'],
					"pays"           => $company['country'],
					"nom"            => '',
					"prenom"         => '',
					"website"        => $company['website'],
					"secteur"        => '',
					"langue"         => $company['language_name'],
					"raison_social"  => $company['name'],
					"type_entreprise"  => '',
					"n_tva"  => '',
					"code_postal"  => $company['zipcode'],
					"ville"  => $company['city'],
				);

				$data["ids"]        = !is_null($company['id']) ? $company['id'] : ids();
				$data["login_type"] = "direct";
				$data["password"]   = md5('123456');
				$data["activation_key"] = $company['id'];
				$data["reset_key"]      = $company['id'];
				$data["created"]    = NOW;
				
				$user = $this->model->get("*", $this->table, "ids = '{$company['id']}'");
				if($user) { // update

					//$this->db->update($this->table, $data, array("ids" => $user->ids));
					//unset($data_info["id_user"]);
					$user_info = $this->model->get("*", 'user_information', "id_user = '{$user->id}'");
					if($user_info) {
						$this->db->update('user_information', $data_info, array("id_user" => $user->id));
					} else {
						$data_info['id_user'] = $user->id;
						$this->db->insert('user_information', $data_info);
					}
				} else {
					$this->db->insert('general_users', $data);
					$data_info['id_user'] = $this->db->insert_id();
					$this->db->insert('user_information', $data_info);
				}
			}
		}
		
		redirect(cn('users/customer')); 
		/*ms(array(
			"status"  => "success",
			"message" => lang("successfully")
		));*/
	}

	function user_import_file(){
		$file = __DIR__."/users_import.csv";
		if( filesize($file) > 0 ) {
			$monfichier = fopen($file, 'r');
			$header = explode(',', fgets($monfichier));
			while ( $ligne = fgets($monfichier)) {
				$company = explode(',', $ligne);
				$id = $company[15];
				// var_dump($id);die;
				$data = array(
					"fullname"        => $company[1],
					"telephone"        => $company[15],
					"email"           => $company[12],
					"package"         => 0,
					"permission"      => "[]",
					"timezone"        => TIMEZONE,
					"expiration_date" => date('Y-m-d'),
					"status"          => $company[22],
					"role"          => 'customer',
					"changed"         => NOW,
					"avatar"         => '',
					"renouvler"         => true,
					"periode"         => 0,
					"rs"         => $company[1],
					"id_team"         => $company[0],
				);

				$data_info = array(
					"id_user"        => "",
					"entreprise"     => $company['name'],
					"SIRET"          => $company[11],
					"adresse"        => $company[2].', '.$company[3],
					"pays"           => $company[6],
					"nom"            => '',
					"prenom"         => '',
					"website"        => $company[13],
					"secteur"        => $company[31],
					"langue"         => $company[7],
					"raison_social"  => $company[1],
					"type_entreprise"  => $company[14],
					"n_tva"  => $company[8],
					"code_postal"  => $company[4],
					"ville"  => $company[5],
				);

				$data["ids"]        = !is_null($company[0]) ? $company[0] : ids();
				$data["login_type"] = "direct";
				$data["password"]   = md5('123456');
				$data["activation_key"] = $company[0];
				$data["reset_key"]      = $company[0];
				$data["created"]    = NOW;
				
				$user = $this->model->get("*", $this->table, "ids = '{$company[0]}'");
				if($user) { // update
					unset($data_info["id_user"]);
					$this->db->update($this->table, $data, array("ids" => $user->ids));
					$user_info = $this->model->get("*", 'user_information', "id_user = '{$user->id}'");
					if($user_info) {
						$this->db->update('user_information', $data_info, array("id_user" => $user->id));
					} else {
						$data_info['id_user'] = $user->id;
						$this->db->insert('user_information', $data_info);
					}
				} else {
					$this->db->insert('general_users', $data);
					$data_info['id_user'] = $this->db->insert_id();
					$this->db->insert('user_information', $data_info);
				}
			}
		}
		redirect(cn('users/customer')); 

	}
	function pack_import_file(){
		$file = __DIR__."/pack_import.csv";
		
		if( filesize($file) > 0 ) {
			$monfichier = fopen($file, 'r');
			$header = explode(',', fgets($monfichier));
			while ( $ligne = fgets($monfichier)) {
				$company = explode(',', $ligne);
				// var_dump($id);die;
				$package = 0;
				
				if (strpos($company[5], 'Starter') !== false) {
					$package = 1;
				}elseif (strpos($company[5], 'Business ') !== false) {
					$package = 8;
				} elseif (strpos($company[5], 'Prime ') !== false) {
					$package = 9;
				}
				$data = array(
					"package"         => $package,
				);
				
				$user = $this->model->get("*", $this->table, "ids = '{$company[0]}'");
				if($user) { // update
					unset($data_info["id_user"]);
					$this->db->update($this->table, $data, array("ids" => $user->ids));					
				} 
			}
		}
		redirect(cn('users/customer')); 

	}

	public function getCompanies() {
		$teamleader_api_group = get_option('teamleader_api_group');
		$teamleader_api_secret = get_option('teamleader_api_secret');
		$users = array();


		if (!empty($teamleader_api_group)) {
			$original_date = "2019-09-12";
			/**
			 * Get the user identity information using the access token.
			 */
			for ($i=6; $i < $i+10 ; $i++) { 
			
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'https://app.teamleader.eu/api/getCompanies.php');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, [
					'api_group' => $teamleader_api_group,
					'api_secret' => $teamleader_api_secret,
					'amount' => 100,
					'pageno' => $i,
				]);
				$response = curl_exec($ch);
				$users = json_decode($response);

				
				$result = array();
				foreach ($users as $key => $company) {
					$add_date = date("Y-m-d", $company->date_added);
					$add_edit = date("Y-m-d", $company->date_edited);
					if($add_date >= $original_date){
						$data = array(
							"fullname"        => $company->name,
							"telephone"        => $company->telephones,
							"email"           => $company->email,
							"package"         => 0,
							"permission"      => "[]",
							"timezone"        => TIMEZONE,
							"expiration_date" => date('Y-m-d'),
							"status"          => 0,
							"role"          => 'customer',
							"changed"         => NOW,
							"avatar"         => '',
							"renouvler"         => true,
							"periode"         => 0,
							"rs"         => $company->name,
							"id_team"         => $company->id,
						);

						$data_info = array(
							"id_user"        => "",
							"entreprise"     => $company->name,
							"SIRET"          => $company->taxcode,
							"adresse"        => $company->street.', '.$company->street,
							"pays"           => $company->country,
							"nom"            => '',
							"prenom"         => '',
							"website"        => $company->website,
							"secteur"        => '',
							"langue"         => $company->language_name,
							"raison_social"  => $company->name,
							"type_entreprise"  => '',
							"n_tva"  => '',
							"code_postal"  => $company->zipcode,
							"ville"  => $company->city,
						);

						$data["ids"]        = !is_null($company->id) ? $company->id : ids();
						$data["login_type"] = "direct";
						$data["password"]   = md5('123456');
						$data["activation_key"] = $company->id;
						$data["reset_key"]      = $company->id;
						$data["created"]    = NOW;
						
						$user = $this->model->get("*", $this->table, "ids = '{$company->id}'");
						if($user) { // update

							//$this->db->update($this->table, $data, array("ids" => $user->ids));
							//unset($data_info["id_user"]);
							$user_info = $this->model->get("*", 'user_information', "id_user = '{$user->id}'");
							if($user_info) {
								$this->db->update('user_information', $data_info, array("id_user" => $user->id));
							} else {
								$data_info['id_user'] = $user->id;
								$this->db->insert('user_information', $data_info);
							}
						} else {
							$this->db->insert('general_users', $data);
							$data_info['id_user'] = $this->db->insert_id();
							$this->db->insert('user_information', $data_info);
						}
					}
				}

			}
			redirect(cn('users/customer'));
			
		}
	}
	public function reconductionUser(){
		$this->db->select("expiration_date,ids");
		$this->db->from(USERS);
		$this->db->where("role = 'customer'");
		$this->db->where("status = 1");
		$this->db->where("renouvler = 1");
		$this->db->where("expiration_date <='".date("Y-m-d")."'");
		$this->db->or_where("expiration_date is null");
		$users=$this->db->get()->result();
		// echo "<pre>";var_dump($users);die();
		foreach($users as $user){
			// $expiration_date=date("Y-m-d", strtotime("+1 years", strtotime($user->expiration_date))); 
			$expiration_date=$user->expiration_date;
			if($expiration_date ==NULL){
				$expiration_date=date("Y-m-d");
			}
			list($year,$month,$day) = explode('-', $expiration_date);
			$year=date("Y")+1;
			// $expiration_date=$year."-".$month."-".$day;
			$this->db->update(USERS, ["expiration_date"=>$expiration_date], array("ids" => $user->ids));
			// echo "<pre>";var_dump($user->mail,$user->expiration_date,$expiration_date);
		}
		// die();
	}
	public function addNewUser(){
		$this->model->sendemailaddNewUser();
	}
}