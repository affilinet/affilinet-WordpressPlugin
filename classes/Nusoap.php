<?php

if (!class_exists('SoapClient')) {

    if (!class_exists('nusoap_client')) {
        require_once (dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR  . 'nusoap/nusoap.php');
    }

    if (!class_exists('SoapClient')) {
        class SoapClient extends nusoap_client {
        }
    }
}