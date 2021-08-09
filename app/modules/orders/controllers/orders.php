<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class orders extends MX_Controller {
	public $table;
	public $columns;
	public $module_name;
	public $module_icon;

	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');

		//Config Module
		$this->table       = 'orders';
		$this->module_name = lang("user_manager");
		$this->module_icon = "fa ft-orders";
	}
	public function index(){
		$data = array();
		//$this->template->build('empty');
		$resultat_api = '{
		    "data": [
		        {
		            "id": "f1dfb84c-3c29-4548-9b9b-9090a080742a",
		            "first_name": "Erlich",
		            "last_name": "Bachman",
		            "salutation": "Mr",
		            "email": "info@piedpiper.eu"
		        },
		        {
		            "id": "f1dfb84c-3c29-4548-9b9b-9090a080742b",
		            "first_name": "John",
		            "last_name": "Doe",
		            "salutation": "Mr",
		            "email": "john@piedpiper.eu"
		        }
		    ],
		    "meta": {
		        "page": {
		            "size": 20,
		            "number": 2
		        },
		        "matches": 200
		    }
		}';
		$orders_data = json_decode($resultat_api);
		$data["orders"] = $orders_data->data;
		$this->template->build('index', $data);
	}

	public function invoices2(){
		$data = array();
		//$this->template->build('empty');
		$invoices = get_invoices();
		$data['invoices'] = $invoices['data'];
		$this->template->build('invoice', $data);
	}

	public function user_invoice_api(){
		
		$company_id = !empty(get('company_id')) ? get('company_id') : 0;
		if($company_id=="--" || $company_id=="0"){
			$user = $this->model->get("*",USERS, "id = ".session("uid"));
			$company_id=$user->id_teamlead_new;
		}
		$teamleader_api_group = get_option('teamleader_api_group');
		$teamleader_api_secret = get_option('teamleader_api_secret');
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
			if($response) {
				$data['invoices'] = json_decode($response, true);
			}
			$this->template->build('invoice_user', $data);
		}

	}

	public function formule_payment() {
		$data = array();
		echo $this->load->view("formule_payment", $data);
	}
	public function user_invoices(){
		
		$result = array();
		$company_id = !empty(get('company_id')) ? get('company_id') : 0;
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
			]);

			$response = curl_exec($ch);
			$data['invoices'] = array();
			// echo "<pre>";var_dump(json_decode($response, true));die();
			if($response) {
				$data['invoices'] = json_decode($response, true);
				// echo "<pre>";
				// var_dump($data['invoices']);

				foreach ($data['invoices'] as $key => $invoice) {
					$user=null;
					if(is_admin()) {
						$user = $this->model->get("*",USERS, "id_teamlead_new = '{$invoice['contact_or_company_id']}'");
					}else if(is_manager()) {
						$users =getUsersByRole($from='acceuil');
						foreach($users as $item){
							if($item->ids==$invoice['contact_or_company_id']){
								$user=$item;
							}
						}
						
					}else if(is_responsable()) {
						$users =getUsersByRole($from='acceuil');
						foreach($users as $item){
							if($item->ids==$invoice['contact_or_company_id']){
								$user=$item;
							}
						}
					}
					if($user){
						$result[] = $invoice;
					}
				}
			}
			// dump($data);die;
			$data['invoices'] = $result;
			$this->template->build('invoice_users', $data);
		} else {

			ms(array(
				"status"  => "error",
				"message" => lang("please_enter_teamleader_api_group")
			));

		}
	}

	public function invoices(){
		$teamleader_api_group = get_option('teamleader_api_group');
		$teamleader_api_secret = get_option('teamleader_api_secret');

		$invoices = array();
		$pos = strrpos(cn(), ".local");

		if (!empty($teamleader_api_group)) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'https://app.teamleader.eu/api/getInvoices.php');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, [
				'date_from' => '01/01/2015',
				'api_group' => $teamleader_api_group,
				'api_secret' => $teamleader_api_secret,
				'date_to' => date('d/m/Y'),
			]);

			$response = curl_exec($ch);
			if($response) {
				get_option('invoices_json2', $response);
				$data['invoices'] = json_decode($response, true);
			} else {
				$data['invoices'] = json_decode(get_option('invoices_json2'));
			}
			$this->template->build('invoice2', $data);
		} else {

			ms(array(
				"status"  => "error",
				"message" => lang("please_enter_teamleader_api_group")
			));

		}
	}

	public function invoices_customer(){
		$teamleader_api_group = get_option('teamleader_api_group');
		$teamleader_api_secret = get_option('teamleader_api_secret');
		$invoices = array();

		if (!empty($teamleader_api_group)) {

			$response = curl_exec($ch);
			$data = json_decode($response, true);
			$accessToken = $data['access_token'];
			if($accessToken == null) {
				$accessToken = $accessTokenDefault;
			}
			/**
			 * Get the user identity information using the access token.
			 */
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'https://api.teamleader.eu/invoices.list');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, [
				'company_id' => '92b8db18-05e9-09f9-9478-1ef681fb95fb',
				'api_group' => $teamleader_api_group,
				'api_secret' => $teamleader_api_secret,
			]);

			$response = curl_exec($ch);
			if($response) {
				$resultat = json_decode($response, true);
				$data['invoices'] = $resultat['data'];
			} else {
				$data['invoices'] = array();
			}
			$this->template->build('invoice_customer', $data);
		} else {

			ms(array(
				"status"  => "error",
				"message" => lang("please_enter_teamleader_api_group")
			));

		}
	}

	public function pdf(){
		$id = get('order_id');
		$teamleader_api_group = get_option('teamleader_api_group');
		$teamleader_api_secret = get_option('teamleader_api_secret');
		$invoices = array();
		if (!empty($teamleader_api_group)) {
		    /**
		     * Get the user identity information using the access token.
		     */
			$curl = curl_init();

			curl_setopt_array($curl, array(
			CURLOPT_URL => "https://app.teamleader.eu/api/downloadInvoicePDF.php",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => array('api_group' => $teamleader_api_group,'api_secret' => $teamleader_api_secret,'invoice_id' => $id),
			CURLOPT_HTTPHEADER => array(
				"Cookie: routing=1596649336.247.10707.786686"
			),
			));

			$response = curl_exec($curl);
			curl_close($curl);
		    $file = "Facture_{$id}.pdf";
			$fileName = "Facture_{$id}.pdf";
			file_put_contents($file, $response);
			// header('Content-type: application/pdf');
			// header('Content-Disposition: inline; filename="' . $filename . '"');
			// header('Content-Transfer-Encoding: binary');
			// header('Content-Length: ' . filesize($file));
			// header('Accept-Ranges: bytes');
		    header("Content-type:application/pdf");
			header("Content-Disposition:attachment;filename=Facture_{$id}.pdf");

			readfile($file);
		} else {
		    ms(array(
				"status"  => "error",
				"message" => lang("please_enter_teamleader_api_group")
			));

		}
	}

	public function pdf_customer(){
		$id = get('order_id');
		$client = get('client');
		$teamleader_api_group = get_option('teamleader_api_group');
		$teamleader_api_secret = get_option('teamleader_api_secret');
		$invoices = array();
		if (!empty($teamleader_api_group)) {
		    /**
		     * Get the user identity information using the access token.
		     */
			$curl = curl_init();

			curl_setopt_array($curl, array(
			CURLOPT_URL => "https://app.teamleader.eu/api/downloadInvoicePDF.php",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => array('api_group' => $teamleader_api_group,'api_secret' => $teamleader_api_secret,'invoice_id' => $id),
			CURLOPT_HTTPHEADER => array(
				"Cookie: routing=1596649336.247.10707.786686"
			),
			));

			$response = curl_exec($curl);
			curl_close($curl);
		    $file = "Facture_{$id}.pdf";
			$fileName = "Facture_{$id}.pdf";
			file_put_contents($file, $response);
			// header('Content-type: application/pdf');
			// header('Content-Disposition: inline; filename="' . $filename . '"');
			// header('Content-Transfer-Encoding: binary');
			// header('Content-Length: ' . filesize($file));
			// header('Accept-Ranges: bytes');
		    header("Content-type:application/pdf");
			header("Content-Disposition:attachment;filename={$client}-Facture_{$id}.pdf");

			readfile($file);
		} else {
		    ms(array(
				"status"  => "error",
				"message" => lang("please_enter_teamleader_api_group")
			));

		}
	}

	public function download(){
		$id = get('order_id');
		$path=APPPATH . '../assets/orders/';
		$name = 'order_example.pdf';
		$content = $path.$name;
		header("Content-type:application/pdf");

		// It will be called downloaded.pdf
		header("Content-Disposition:attachment;filename=".$name);

		// The PDF source is in original.pdf
		readfile($content);
		// echo $content;
	}

}