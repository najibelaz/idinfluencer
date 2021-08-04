<?php
defined('BASEPATH') OR exit('No direct script access allowed');
function date_compare($a, $b){
    $t1 = strtotime($a->created);
    $t2 = strtotime($b->created);
    return $t2 - $t1;
} 

class post_model extends MY_Model {
	public function __construct(){
		parent::__construct();
	}


	
}
