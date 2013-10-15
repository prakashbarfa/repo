<?php

defined('BASEPATH') OR exit('No direct script access allowed');

# disable php error reporting. don't want to polute output with notices etc.
# ini_set('display_errors', 'Off');
# error_reporting(0);

ini_set('display_errors', 'On');
error_reporting(E_ALL);

class Price_api extends REST_APIS {

    protected $rest_format = 'json';

    public function __construct() {
        parent::__construct();
        # fudge a HTTP POST for the form validation library and other CI libs, which don't respect query strings for PUT and DELETE.
        $_POST = & $this->_args;
        $this->load->model('Price_m');
        header('Cache-Control: no-cache');
    }

    function spotprice_get() {
        $data = $this->Price_m->get_spots('both', $this->get('when'));
        $this->response($data, 200);
    }

    function spotprice_post() {
        if (isset($_POST['price_pause']) && $_POST['price_pause'] == 1) {
            $this->Price_m->price_pause('both');
            $this->response(array(), 204);
        } else {
            $price = trim($_POST['price']);

            if (isset($_POST['timeend']))
                $dateend_r = date("d/m/Y") . " " . $_POST['timeend'];

            if (isset($_POST['dateend']))
                $dateend_r = $_POST['dateend'];

            $dateend = DateTime::createFromFormat('d/m/Y G:i', $dateend_r);

            $datenow = new DateTime();

            if ($dateend < $datenow)
                throw new REST_Exception('date end should be greater than current date', 400);
            if (!is_numeric($price))
                throw new REST_Exception('Please enter a valid price', 400);

            $vol_max = (isset($_POST['vol_max']) && $_POST['vol_max'] > 0 ? intval($_POST['vol_max']) : 0);

            $this->Price_m->price_update($price, $dateend->format('Y-m-d H:i'), 'both', NULL, $vol_max);
            $this->response(array(), 204);
        }
    }

    function price_limit_get() {
        $data = $this->Price_m->get_price_limit();
        $this->response($data, 200);
    }

    function price_limit_post() {
        $data = $this->Price_m->save_price_limit(intval($this->input->post('limit_value')));
        $this->response(array(), 204);
    }

    function price_topup_post() {
        $data = $this->Price_m->save_price_topup(intval($this->input->post('id')), $this->input->post('topup'));
        $this->response(array(), 204);
    }

}