<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class jeu_concours extends MX_Controller {
	public $table;
	public $columns;
	public $module_name;
	public $module_icon;

	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');

		//Config Module
		$this->table       = "jeux_concours";
		$this->module_name = lang("jeu_concours");
		$this->module_icon = "fa ft-game";
		$this->columns = array(
			"id"            => lang("id"),
			"name"          => lang("name"),
			"q1"            => lang("q1"),
			"q2"            => lang("q2"),
			"q3"            => lang("q3"),
			"img"           => lang("img"),
			"description"   => lang("description"),
			"reglement"     => lang("reglement"),
			"date_debut"    => lang("date_debut"),
			"date_fin"      => lang("date_fin"),
			"id_user"       => lang("id_user")
		);
	}
	public function update(){

		$data = array(
			"result"      => $this->model->get("*", $this->table, "ids = '".segment(3)."'"),       
			"module"      => get_class($this),
			"module_name" => $this->module_name,
			"module_icon" => $this->module_icon,
		);

		$this->template->build('update', $data);
	}
	public function index(){
		$data = array(
			"result"      => $this->model->getallgames(),       
			"module"      => get_class($this),
			"module_name" => $this->module_name,
			"module_icon" => $this->module_icon,
		);
		$this->template->build('list', $data);
	}
	public function participant(){
		$data = array(
			"result"      => $this->model->getallparticipant("participant_jeux"),       
			"module"      => get_class($this),
			"module_name" => $this->module_name,
			"module_icon" => $this->module_icon,
		);
		$this->template->build('list_participant', $data);
	}
	public function gagnant($id = ""){
		$participant = $this->model->fetch("*", "participant_jeux","id_game = '".$id."'");

		$game = $this->model->get("*", $this->table, "ids = '{$id}'");
		$data['result'] = $participant;
		$data['game'] = $game;
		$data['user'] = 0;
		if ($game->id_gagnant) {
			$data['user'] = $this->model->get("*", "participant_jeux", "id = '".$game->id_gagnant."'");
		}
		$this->template->build('chose_gagnant', $data);

	}
	public function detail_jeu($id = ""){
		$participant = $this->model->fetch("*", "participant_jeux","id_game = '".$id."'");

		$game = $this->model->get("*", $this->table, "ids = '{$id}'");
		$data['result'] = $participant;
		$data['game'] = $game;
		$this->template->build('view_details', $data);

	}
	public function generate_winner($id = ""){
		$participant = $this->model->fetch("*", "participant_jeux","id_game = '".$id."'");
		if($participant){
			$participant_id = array_rand($participant, 1);
			$participant = $participant[$participant_id];
			$this->model->setGagnant($participant,$id);
			ms(array(
				"status"  => "success",
				"nameg"  => $participant->nom." ".$participant->prenom,
				"ids"  => $participant->ids,
				"message" => sprintf(lang("le_gagnant_est_"),$participant->nom." ".$participant->prenom)
			));
		}else{
			ms(array(
				"status"  => "error",
				"message" => lang("aucun_participant")
			));
		}
	}
	public function ajax_update(){
		$id_game          = post("id_game");
		$name             = post("name");
		$q1               = post("q1");
		$q2               = post("q2");
		$q3               = post("q3");
		$img              = post("media");
		$img2              = post("media2");
		$description      = post("description");
		$reglement        = post("reglement");
		$reglement1        = post("reglement1");
		$date_debut       = post("date_debut");
		$date_fin         = post("date_fin");
		$id_user          = post("id_user");
		$email_jeu          = post("email_jeu");
		$pass          = post("pass");
		$objet_mail          = post("object_mail");
		$email_participant          = post("email_participant");
		$object_parrain          = post("object_parrain");
		$email_parrain          = post("email_parrain");
		$text_jeu_termine          = post("text_jeu_termine");
		$btn_jeu_termine          = post("btn_jeu_termine");


		

		if($name == ""){
			ms(array(
				"status"  => "error",
				"message" => lang("please_enter_game")
			));
		}
		

		if($description == ""){
			ms(array(
				"status"  => "error",
				"message" => lang("please_enter_description")
			));
		}

		if($date_debut == ""){
			ms(array(
				"status"  => "error",
				"message" => lang("please_enter_date_debut")
			));
		}

		if($date_fin == ""){
			ms(array(
				"status"  => "error",
				"message" => lang("please_enter_date_fin")
			));
		}

		// if($reglement == ""){
		// 	ms(array(
		// 		"status"  => "error",
		// 		"message" => lang("please_enter_réglement")
		// 	));
		// }
		if($id_user == ""){
			ms(array(
				"status"  => "error",
				"message" => lang("please_enter_phone")
			));
		}


		if($reglement){
			$reglement1 = $reglement;
		}

		$date_fin = str_replace('/', '-', $date_fin );
		$date_fin = date("Y-m-d H:i:s", strtotime($date_fin));
		$date_debut = str_replace('/', '-', $date_debut );
		$date_debut = date("Y-m-d H:i:s", strtotime($date_debut));
		//
		$data = array(
			"name"        => $name,
			"ids"        => ids(),
			"q1"        => $q1,
			"q2"           => $q2,
			"q3"         => $q3,
			"img"      => !is_null($img[0]) ? $img[0] : '',
			"img2"      => !is_null($img2[0]) ? $img2[0] : '',
			"description"        => $description,
			"reglement" => !is_null($reglement1[0]) ? $reglement1[0] : '',
			"date_debut"          => $date_debut,
			"date_fin"          => $date_fin,
			"id_user"          => $id_user,
			"email_jeu"          => $email_jeu,
			"objet_mail"          => $objet_mail,
			"email_participant"          => $email_participant,
			"object_parrain"          => $object_parrain,
			"email_parrain"          => $email_parrain,
			"text_jeu_termine"          => $text_jeu_termine,
			"btn_jeu_termine"          => $btn_jeu_termine,
			"pass"          => $pass,
		);
		$game = $this->model->get("*", $this->table, "id = '{$id_game}'");
		
		if(empty($game)){
			$this->db->insert($this->table, $data);			
			$id = $this->db->insert_id();
			$this->load->helper('text');
			$this->load->helper('url');
			$slug = url_title(convert_accented_characters($id.'-'.$name), 'dash', true);
			$dataup = array(
				"slug" => $slug
			);
			$this->db->update($this->table, $dataup, array("id" => $id));			
		}else{
			unset($data["ids"]);
			$this->db->update($this->table, $data, array("id" => $game->id));
		}
		
		ms(array(
			"status"  => "success",
			"message" => lang("successfully")
		));
	}
	public function upload_files(){
		get_upload_folder();

		$types = "";

		$config['upload_path']          = './assets/uploads/user/jeux'.$this->userid;
        $config['allowed_types']        = $types;
        $config['max_size']             = $this->max_size;
        $config['max_width']            = 0;
        $config['max_height']           = 0;
        $config['encrypt_name']         = TRUE;


        $this->load->library('upload', $config);
		$info;
		
        if(!empty($_FILES)){
	        $files = $_FILES;
		    for($i=0; $i< count($_FILES['files']['name']); $i++){  
		        $_FILES['files']['name']= $files['files']['name'][$i];
		        $_FILES['files']['type']= $files['files']['type'][$i];
		        $_FILES['files']['tmp_name']= $files['files']['tmp_name'][$i];
		        $_FILES['files']['error']= $files['files']['error'][$i];
		        $_FILES['files']['size']= $files['files']['size'][$i];
		        
		        $this->model->get_storage("file", $_FILES['files']['size']/1024);
		        $this->upload->initialize($config);
		        if (!$this->upload->do_upload("files"))
		        {
	                ms(array(
	                	"status"  => "error",
	                	"message" => $this->upload->display_errors()
	                ));
		        }
		        else
		        {
		        	$info = (object)$this->upload->data();

		        }
		    }
        }else{
        	return false;
		}
		return $info;
	}
	public function ajax_delete_item($ids = null){
		if($ids != null){
			$this->db->delete($this->table, array("ids" =>$ids));
			ms(array(
				"status"  => "success",
				"message" => lang("successfully")
			));
		}
	}

	public function ajax_reset_item(){
		$this->db->delete('participant_jeux', array("id_game" =>post("id")));
		ms(array(
			"status"  => "success",
			"message" => lang("successfully")
		));
	}
	public function show_game($ids = ""){
		$game = $this->model->get("*", $this->table, "ids = '".$ids."'");
		
		if ($game) {
			$data =array();
			$data["game"] = $game;
			$data["template"] = 'game';
			$this->template->set_layout('game');
			$this->template->build('show', $data);
		}else{
			redirect(cn("jeu_concours"));
		}
	}
	public function preview_participant($ids = ""){
		$participant = $this->model->get("*", "participant_jeux", "ids = '".$ids."'");
		
		if ($participant) {
			$game = $this->model->get("*", $this->table, "ids = '".$participant->id_game."'");
			$data =array();
			$data["participant"] = $participant;
			$data["game"] = $game;
			$this->template->build('view', $data);
		}else{
			redirect(cn("jeu_concours"));
		}
	}
	public function participer($ids = ""){
		$game = $this->model->get("*", $this->table, "ids = '".$ids."'");
		
		if ($game) {
			$strtime_date_fin =  strtotime($game->date_fin) ;
			$strtime_date_now =  strtotime(date('Y-m-d H:i:s')) ;
			$diff = $strtime_date_fin - $strtime_date_now;
			$data =array();
			$data["game"] = $game;
			$this->template->set_layout('game');
			if($diff > 0){
				$this->template->build('participer', $data);
			}else{
				if($game->id_gagnant ==0){
					$data["id_gagnant"] = 0;
				}else{
					$data["id_gagnant"] = 1;
				}
				$this->template->build('end_game', $data);
			}
		}else{
			redirect(cn("jeu_concours"));
		}
	}
	public function participer_front($ids = ""){
		$game = $this->model->get("*", $this->table, "ids = '".$ids."'");
		
		if ($game) {
			$strtime_date_fin =  strtotime($game->date_fin) ;
			$strtime_date_now =  strtotime(date('Y-m-d H:i:s')) ;
			$diff = $strtime_date_fin - $strtime_date_now;
			$data =array();
			$data["blank_page"] = $game;
			$this->template->set_layout('game');
			if($diff > 0){
				$this->template->build('participer', $data);
				$this->template->build('../../../themes/'.get_theme().'/views/participer', $data);
			}else{
				if($game->id_gagnant ==0){
					$data["id_gagnant"] = 0;
				}else{
					$data["id_gagnant"] = 1;
				}
				$this->template->build('../../../themes/'.get_theme().'/views/end_game', $data);
			}
		}else{
			redirect(cn("jeu_concours"));
		}
	}
	public function success($ids = ""){
		$participant = $this->model->get("*", 'participant_jeux', "ids = '".$ids."'");
		if ($participant) {
			$data =array();
			$data["participant"] = $participant;
			$this->template->set_layout('game');
			$this->template->build('success', $data);
		}else{
			redirect(cn("jeu_concours"));
		}
	}
	public function add_participant(){
		// echo post("rep_q2");exit;
		$email           = post("email");
		$prenom          = post("prenom");
		$nom             = post("nom");
		$phone           = post("phone");
		$code_postal     = post("code_postal");
		$etat_civil      = post("etat_civil");
		$address         = post("address");
		$reglement       = post("reglement");
		$newsletter      = post("newsletter");
		$rep_q1          = post("rep_q1");
		$rep_q2          = post("rep_q2");
		$rep_q3          = post("rep_q3");
		$id_game         = post("id_game");
		$adress_ip       = get_client_ip();

		$game = $this->model->get("*", $this->table, "ids = '".$id_game."'");
		
		if($email == ""){
			ms(array(
				"status"  => "error",
				"message" => lang("please_enter_email")
			));
		}
		if($prenom == ""){
			ms(array(
				"status"  => "error",
				"message" => lang("firstname_is_required")
			));
		}
		if($nom == ""){
			ms(array(
				"status"  => "error",
				"message" => lang("name_is_required")
			));
		}

		if($phone == ""){
			ms(array(
				"status"  => "error",
				"message" => lang("phone_required")
			));
		}

		if($code_postal == ""){
			ms(array(
				"status"  => "error",
				"message" => lang("code_postal_required")
			));
		}

		if($etat_civil == ""){
			ms(array(
				"status"  => "error",
				"message" => lang("etat_civil_required")
			));
		}

		if($reglement == ""){
			ms(array(
				"status"  => "error",
				"message" => lang("reglement_required")
			));
		}
		// if($newsletter == ""){
		// 	ms(array(
		// 		"status"  => "error",
		// 		"message" => lang("please_enter_réglement")
		// 	));
		// }
		if($address == ""){
			ms(array(
				"status"  => "error",
				"message" => lang("adresse_required")
			));
		}

		if($rep_q1 == ""){
			$rep_q1 = 0;
		}

		if($rep_q2 == ""){
			$rep_q2 = 0;
		}

		if($rep_q3 == ""){
			$rep_q3 = 0;
		}
		$now = time(); // or your date as well
		$date_fin = $game->date_fin;	
		$date_fin = strtotime($date_fin);
		$date_debut = $game->date_debut;	
		$date_debut = strtotime($date_debut);
		$datefin = $now - $date_fin ;
		$datedebut = $now - $date_debut ;
		if($datefin > 0 || $datedebut < 0){
			ms(array(
				"status"  => "error",
				"message" => lang("Expired")
			));
		}
		$date_fin = date("Y-m-d h:i", strtotime($date_fin));
		$date_debut = date("Y-m-d h:i", strtotime($date_debut));
		

		$ids = ids();
		$data = array(
			"email"          => $email,
			"ids"          => $ids,
			"nom"          => $nom,
			"prenom"          => $prenom,
			"phone"          => $phone,
			"code_postal"          => $code_postal,
			"etat_civil"          => $etat_civil,
			"address"          => $address,
			"reglement"          => $reglement,
			"newsletter"          => $newsletter,
			"rep_q1"          => $rep_q1,
			"rep_q2"          => $rep_q2,
			"rep_q3"          => $rep_q3,
			"id_game"          => $id_game,
			"adress_ip"          => $adress_ip,
		);
		$participant = $this->model->get("*", 'participant_jeux', "email = '{$email}' and adress_ip = '{$adress_ip}'");
		if(empty($participant)){
			$this->db->insert('participant_jeux', $data);
			// redirect('jeux_concours/success/'.$ids);			
			ms(array(
				"status"  => "success",
				"ids"  => $ids,
				"message" => lang("done")
			));
		}else{
			ms(array(
				"status"  => "error",
				"message" => lang("already_exists_in_game")
			));
		}
		
	}
	public function popup(){
		$this->load->view('popup_game');
	}

	public function get_Game($page = 0){
		$limit = 10;
		$start = $page * $limit;
		$next_start = ($page+1) * $limit;
		$Games = $this->model->fetch("*", $this->table, "id_user = '".user_or_cm()."'", "id", "desc", $start, $limit);
		$next_Games = $this->model->fetch("*", $this->table, "id_user = '".user_or_cm()."'", "id", "desc", $next_start, $limit);
		$data = array(
			"limit" => $limit,
			"page" => $page,
			"Games" => $Games,
			"next_Games" => $next_Games
		);

		$this->load->view('get_game', $data);
	}	

}