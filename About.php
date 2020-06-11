<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class About extends CI_Controller {
	
    function __construct() {
        parent::__construct();
        $this->load->model('global_model');
        $this->load->model('content_model');
    }

    public function index() {
        $data = null;
        $data['aboutus_content'] = $this->db->query("SELECT * FROM `aboutus_content` ORDER BY id ASC")->result();
        $data['aboutus_team'] = $this->db->query("SELECT * FROM `aboutus_team` WHERE publish = '1' ORDER BY short DESC")->result();
        $data['aboutus_partner'] = $this->db->query("SELECT * FROM `aboutus_partner` WHERE publish = '1' ORDER BY short DESC")->result();
        $data['property_title'] = $this->config->item('site_title');
        $data['property_desc'] = $this->config->item('site_description');
        $data['footer_contact_info'] = $this->content_model->get_footer_contact_info();
        $this->load->view('about', $data);
    }
    
}