<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends Admin_Base {

    function __construct() {
        parent::__construct();
		$this->load->model('profile_model');
    }

    function get($type_id, $aux_id) {
		//echo "group_id: " . $this->group_id;

//$this->output->enable_profiler(true);
		if (!$row = $this->profile_model->get_aux($type_id, $aux_id))
			show_error("profile not found", 404);

		print_r( $row);
        //$data['head_content'] = 'form_builder/head';
        //$data['main_content'] = 'form_builder/layout';
        //$this->load->view('admin/template', array_merge($data, $row));
    }
}
