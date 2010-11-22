<?php
class Welcome extends MY_Controller {

    function Welcome() {
        parent::MY_Controller();
    }

    function index() {
        $this->view();
    }

}
