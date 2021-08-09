<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Mailjet\Client;
use Mailjet\Resources;

class mailjet extends MX_Controller {
	public $table;
	public $columns;
	public $module_name;
	public $module_icon;

	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');

		//Config Module
		$this->table       = "mailjet";
		$this->module_name = lang("mailjet");
		$this->module_icon = "fa ft-mail";
		
	}
	public function index(){
		
		$apikey = get_option('mailjet_api');
		$apisecret = get_option('mailjet_secret');
		$id = 2069403;
		$mj = new Client($apikey, $apisecret,true,['version' => 'v3']);
		$filters = [
			'IsExcludedFromCampaigns' => 'false'
		];
		$response = $mj->get(Resources::$Contactstatistics);
		$response->success();
		$data = array(
			"result"      => $response->getData(),       
			"module"      => get_class($this),
			"module_name" => $this->module_name,
			"module_icon" => $this->module_icon,
		);
		$this->template->build('stats', $data);
	}
	public function contact_list(){

		$apikey = get_option('mailjet_api');
		$apisecret = get_option('mailjet_secret');
		$id = 2069403;
		$mailjet = new Client($apikey, $apisecret);
		$filters = [
			'IsExcludedFromCampaigns' => 'false'
		];
		$response = $mailjet->get(Resources::$Contact, ['filters' => $filters]);
		$response->success();
		// $body = [
		// 	'Email' => 'test11@test.com',
		// 	'Action' => "addnoforce",
		// ];
		// $result = $mailjet->post(Resources::$ContactslistManagecontact, ['id' => $id, 'body' => $body]);
		// dump($result);
		// $ResultMessage= array();
		// if ($result->getReasonPhrase() == "Created") {
		// 	$ResultMessage['message'] = 'Successful registration';
		// 	$ResultMessage['status'] = 'done';
		// }else{
		// 	$ResultMessage['message'] = 'An error occurred while subscribing to the newsletter';
		// 	$ResultMessage['status'] = 'fail';
		// }
		$data = array(
			"result"      => $response->getData(),       
			"module"      => get_class($this),
			"module_name" => $this->module_name,
			"module_icon" => $this->module_icon,
		);
		$this->template->build('index', $data);
	}

	public function contact_show($id = null){
		if($id == null){
			redirect('mailjet');
		}
		$apikey = get_option('mailjet_api');
		$apisecret = get_option('mailjet_secret');	
		$mj = new Client($apikey, $apisecret,true,['version' => 'v3']);
		$response = $mj->get(Resources::$Contactstatistics);
		$response->success();
		$data = $response->getData();
		$contact = array();
		foreach ($data as $value) {
			if($id == $value['ContactID']){
				$contact = $value;
			}
		}
		$this->template->build('mailjet_show.php', $contact);
	}
	
	
	
}