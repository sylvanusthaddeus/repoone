<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends CI_Controller {
	
    function __construct() {
        parent::__construct();
        $this->load->model('global_model');
        $this->load->model('content_model');
    }

    public function index() {
    	$this->list_();
    }
    
    function list_() {
        $param_paging = NULL;
        $limit = 6;
        $offset = 0;
        
        $page = $this->input->get('page');
        if(!empty($page)) {
            $offset = (($this->input->get('page')*1)*$limit)-$limit;
        }
        
    	$data['cur_id_cat'] = '0';
    	$uri3 = explode('-', $this->uri->segment(3));
    	$id_cat = end($uri3);
        $id_cat = $this->db->escape_str($id_cat);
        $where_search = "WHERE a.publish = '1' ";
        if(!empty($id_cat)) {
			$where_search .= " AND a.project_cat_id = '$id_cat'";
			$data['cur_id_cat'] = $id_cat;
            $param_paging .= $this->uri->segment(3);
		}
        $data['project_cat'] = $this->db->query("SELECT * FROM `project_cat` WHERE publish = '1' ORDER BY short DESC")->result();
        
        $q ="SELECT a.*, b.title AS category FROM `project` a LEFT JOIN project_cat b ON b.id = a.project_cat_id $where_search";
        $q2 = $q."
                ORDER BY a.short DESC
                LIMIT $offset, $limit";
        $sql = $this->db->query($q2);
        $res = $sql->result();
        
        $q_total = $this->db->query($q);
        $total_rows = $q_total->num_rows();
        $this->load->library('pagination');
        $config['base_url']             = base_url()."project/list_/$param_paging/?";
        $config['total_rows']           = $total_rows;
        $config['per_page']             = $limit;
        $config['use_page_numbers']     = TRUE;
        $config['num_links']            = 2;
        $config['page_query_string']    = TRUE;
        $config['query_string_segment'] = 'page';
        
        
        $config['first_link']       	= '«';
        $config['last_link']        	= '»';
        $config['next_link']        	= '&gt;';
        $config['prev_link']        	= '&lt;';
        $config['full_tag_open']    	= '<ul class="pagination justify-content-center margin-top-70">';
        $config['full_tag_close']   	= '</ul>';
        $config['num_tag_open']     	= '<li class="page-item"><div class="page-link">';
        $config['num_tag_close']    	= '</span></li>';
        $config['cur_tag_open']     	= '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close']    	= '<span class="sr-only">(current)</span></span></li>';
        $config['next_tag_open']    	= '<li class="page-item"><span class="page-link">';
        $config['next_tagl_close']  	= '<span aria-hidden="true">&raquo;</span></span></li>';
        $config['prev_tag_open']    	= '<li class="page-item"><span class="page-link">';
        $config['prev_tagl_close']  	= '</span>Next</li>';
        $config['first_tag_open']   	= '<li class="page-item"><span class="page-link">';
        $config['first_tagl_close'] 	= '</span></li>';
        $config['last_tag_open']    	= '<li class="page-item"><span class="page-link">';
        $config['last_tagl_close']  	= '</span></li>';
        $this->pagination->initialize($config);
        
        
        
        $data['project'] = $res;
        $data['property_title'] = $this->config->item('site_title');
        $data['property_desc'] = $this->config->item('site_description');
        $data['footer_contact_info'] = $this->content_model->get_footer_contact_info();
        $this->load->view('project', $data);
	}
	
	function detail() {
    	$uri1 = explode('-', $this->uri->segment(3));
    	$id = end($uri1);
        $id = $this->db->escape_str($id);
        $data['content'] = $this->db->query("SELECT a.*, b.title AS category FROM `project` a LEFT JOIN project_cat b ON b.id = a.project_cat_id WHERE a.publish = '1' AND a.id = '$id'")->row();
        $data['property_title'] = $this->config->item('site_title');
        $data['property_desc'] = $this->config->item('site_description');
        $data['footer_contact_info'] = $this->content_model->get_footer_contact_info();
        $this->load->view('project_detail', $data);
	}
}