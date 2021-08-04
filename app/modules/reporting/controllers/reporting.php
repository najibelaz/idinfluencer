<?php
defined('BASEPATH') or exit('No direct script access allowed');

class reporting extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(get_class($this) . '_model', 'model');
    }

    public function index()
    {
        $mois = (int)date('m');
        $annee = (int)date('Y');
        if($mois == 1) {
            $annee = $annee - 1;
        }
        $rs = get('rs');
        $idsrs = get('idsrs');
        $dateFrom = $annee."-".($mois-2)."-01";
        $dateTo = $annee."-".($mois-1)."-31";
        $data['user'] = $this->model->get("*", USERS, "id = '".session("uid")."'");
        $data['grp'] = $this->model->get_groups();
        $data['dateFrom'] = $dateFrom;
        $data['dateTo'] = $dateTo;
        $account_info = get_accounts($row->ids);
        $stats = array();
        if($rs) {
            $stats = Modules::run("facebook/stats");
        }
        $data['stats'] = $stats;
        //$stats = Modules::run("facebook/stats");
        $this->template->build('report', $data);
    }
}
