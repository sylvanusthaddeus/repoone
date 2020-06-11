<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	
    function __construct() {
        parent::__construct();
        $this->load->model('global_model');
        $this->load->model('content_model');
    }

    public function index() {
        $data = null;
        $data['content'] = $this->db->query("SELECT * FROM `home_setting` WHERE id = '1'")->row();
        $data['property_title'] = $this->config->item('site_title');
        $data['property_desc'] = $this->config->item('site_description');
        $data['footer_contact_info'] = $this->content_model->get_footer_contact_info();
        $this->load->view('home', $data);
    }
    
}