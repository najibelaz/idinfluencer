<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class post_model extends MY_Model {
	public $tb_accounts;
	public $tb_posts;

	public function __construct(){
		parent::__construct();
	}
}
