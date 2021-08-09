<?php
defined('BASEPATH') or exit('No direct script access allowed');

class test extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(get_class($this) . '_model', 'model');
    }

    public function index()
    {
        $data = array();
        $this->template->title('Dashboard');
        $this->template->build('index', $data);
    }

    public function waiting()
    {
        $data = array();
        $this->template->title('Dashboard');
        $this->template->build('waiting', $data);
    }

    public function post()
    {
        $accounts = $this->model->get_accounts();

        $data = array(
            "accounts" => $accounts,
        );

        $this->template->build('post', $data);
    }

    public function caption()
    {

        $this->template->build('caption');
    }

    public function schedules()
    {

        $this->template->build('schedules');
    }

    public function schedules_admin()
    {

        $this->template->build('schedules_admin');
    }

    public function calendartest()
    {

        $this->template->build('calendartest');
    }

    public function cmdashbord()
    {

        $this->template->build('cmdashbord');
    }

    public function client_view()
    {

        $this->template->build('client_view');
    }
    public function draft()
    {

        $this->template->build('draft');
    }
    public function models()
    {

        $this->template->build('models');
    }

    public function jeux_concours_add()
    {

        $this->template->build('jeux_concours/add');
    }

    public function jeux_concours_list()
    {
        $this->template->build('jeux_concours/list_jeux');
    }

    public function jeux_concours_show()
    {
        $this->template->build('jeux_concours/show');
    }

    public function jeux_concours_success()
    {
        $this->template->build('jeux_concours/sccess');
    }

    public function jeux_concours_gagnant()
    {
        $this->template->build('jeux_concours/gagnant');
    }
    public function jeux_concours_fermeture()
    {
        $this->template->build('jeux_concours/fermeture');
    }

    public function raport_mail()
    {
        $this->template->build('raport_mail');
    }
}
