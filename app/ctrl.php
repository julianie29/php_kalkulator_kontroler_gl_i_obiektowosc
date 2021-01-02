<?php

require_once dirname(__FILE__).'/../config.php';

$action = $_REQUEST['action'];

switch ($action) {
    default :
        include_once $config->root_path.'/app/calc/CalcCtrl.class.php';
        $ctrl = new CalcCtrl ();
        $ctrl->generateView ();
        break;
    case 'calcCompute' :
        // załaduj definicję kontrolera
        include_once $config->root_path.'/app/calc/CalcCtrl.class.php';
        $ctrl = new CalcCtrl ();
        $ctrl->process ();
        break;
}