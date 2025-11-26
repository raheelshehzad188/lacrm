<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Companies extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }

    public function index()
    {
        $this->load->view('companies');
    }

    public function add()
    {
        // TODO: Add company form
        echo "Add company form - Coming soon";
    }
}

