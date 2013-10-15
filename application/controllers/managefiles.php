
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Managefiles extends Admin_Base {

    function index() {
        if (!$this->is_suadmin)
            show_error("you can't access this page");
        
        $this->data['main_content'] = 'admin/filemanager';
        $this->load->view('admin/template', $this->data);
   
    }

    public function connector() {
        
        
        
        error_reporting(0); // Set E_ALL for debuging
        include_once FCPATH . 'filemanager/php/elFinderConnector.class.php';
        include_once FCPATH . 'filemanager/php/elFinder.class.php';
        include_once FCPATH . 'filemanager/php/elFinderVolumeDriver.class.php';
        include_once FCPATH . 'filemanager/php/elFinderVolumeLocalFileSystem.class.php';
        $opts = array(
            'debug' => true,
            'roots' => array(
                array(
                    'driver' => 'LocalFileSystem', // driver for accessing file system (REQUIRED)
                    'path' => FCPATH.'/client_assets', // path to files (REQUIRED)
                    'URL' => BASE_URL . '/client_assets', // URL to files (REQUIRED)
                    'accessControl' => 'access'             // disable and hide dot starting files (OPTIONAL)
                ),
                array(
                    'driver' => 'LocalFileSystem', // driver for accessing file system (REQUIRED)
                    'path' => '/home/formbay11/public_html', // path to files (REQUIRED)
                    'URL' => 'http://formbay.com.au', // URL to files (REQUIRED)
                    'accessControl' => 'access'             // disable and hide dot starting files (OPTIONAL)
                )
            )
        );



        $connector = new elFinderConnector(new elFinder($opts));
        $connector->run();
    }

    function theme() {
        $this->load->helper('form');
        $this->load->view('admin/theme');
    }

    function do_upload() {

        $path_parts = pathinfo($_POST['filepath']);
        $config['upload_path'] = $path_parts['dirname'];
        $config['file_name'] = $path_parts['basename'];
        unlink($_POST['filepath']);
        //  $config['file_name'] = $path_parts['basename'];
        $config['allowed_types'] = 'gif|jpg|png';
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload()) {
            $error = array('error' => $this->upload->display_errors());

            var_dump($error);
        } else {
            $data = array('upload_data' => $this->upload->data());

            redirect('managefiles/theme');
        }
    }

}