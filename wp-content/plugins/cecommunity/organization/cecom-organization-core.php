<?php

function do_dummy_print() {
    echo "This is a dummy print!!!!";
}

add_action("dummyprint", "do_dummy_print");

class CECOM_Organization {

    
    public function __construct() {
        echo "i have been generated";
    }
    public $test = "testaaaaaaaaaaaa";

    function print_dummy() {
        echo "this is dummy static print";
    }

}

function cecom_setup_organization() {

    //self::$instance->organization = new CECOM_Organization();
    echo "setup organization";
}

add_action('cecom_setup_components', 'cecom_setup_organization', 10);
?>
