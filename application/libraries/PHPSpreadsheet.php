<?php

defined('BASEPATH') || exit('Access Denied.');

//require_once APPPATH .'third_party/PhpSpreadsheet/IOFactory.php';

class PHPSpreadsheet {
    private $CI;
    private $config;
    private $adapter;
    private $PHPSpreadsheet;

    public function __construct() {
        $this->CI =& get_instance();
        
//        require_once APPPATH .'third_party/PhpSpreadsheet/IOFactory.php';
        
        $this->PHPSpreadsheet = new \PhpOffice\PhpSpreadsheet();
    }

    public function readfile($filename) {
//        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
//        var_dump($reader);die;
        $reader = $this->PHPSpreadsheet;
                var_dump($reader);die;
        $spreadsheet = $reader->load($filename);
        print_r($spreadsheet);die;
    }
}