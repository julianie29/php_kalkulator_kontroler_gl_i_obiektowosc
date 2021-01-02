<?php

require_once $config->root_path.'/lib/smarty/Smarty.class.php';
require_once $config->root_path.'/lib/Messages.class.php';
require_once $config->root_path.'/app/calc/CalcForm.class.php';
require_once $config->root_path.'/app/calc/CalcResult.class.php';

class CalcCtrl
{
    private $form;
    private $messages;
    private $result;

    public function __construct(){
        $this->form = new CalcForm();
        $this->result = new CalcResult();
        $this->messages = new Messages();
    }

    function getParams(){
        $this->form->value = isset($_REQUEST ['value']) ? $_REQUEST['value'] : null;
        $this->form->time = isset($_REQUEST ['time']) ? $_REQUEST['time'] : null;
        $this->form->interest = isset($_REQUEST ['interest']) ? $_REQUEST['interest'] : null;
    }

    function validate(){
        if (!(isset($this->form->value) && isset($this->form->time) && isset($this->form->interest))) {
            return false;
        }

        if ( $this->form->value == "") $this->messages->addError('Nie podano kwoty kredytu');
        if ( $this->form->time == "") $this->messages->addError('Nie podano czasu kredytowania');
        if ( $this->form->interest == "") $this->messages->addError('Nie podano oprocentowania');


        if (!is_numeric($this->form->value)) $this->messages->addError('Kwota kredytu nie jest liczbą');
        if (!is_numeric($this->form->time)) $this->messages->addError('Okres kredytowania nie jest liczbą');
        if (!is_numeric($this->form->interest)) $this->messages->addError('Oprocentowanie nie jest liczbą');

        return ! $this->messages->isError();
    }

    function process(){
        $this->getParams();

        if ($this->validate()) {
            //konwersja parametrów na float
            $this->form->value = floatval($this->form->value);
            $this->form->time = floatval($this->form->time);
            $this->form->interest = floatval($this->form->interest);

            $this->result->result = (($this->form->value * (100 + $this->form->interest)) / 100) / ($this->form->time * 12);
        }
        $this->generateView();
    }

    public function generateView()
    {
        global $config;

        $smarty = new Smarty();

        $smarty->assign('config',$config);
        $smarty->assign('page_title','Kalkulator kredytowy');
        $smarty->assign('page_description','Darmowy kalkulator kredytowy, dzięki któremu w szybki i łatwy sposób obliczysz ratę swojego kredytu');
        $smarty->assign('page_header','Szablony Smarty');

        //pozostałe zmienne niekoniecznie muszą istnieć, dlatego sprawdzamy aby nie otrzymać ostrzeżenia
        $smarty->assign('frm',$this->form);
        $smarty->assign('res',$this->result);
        $smarty->assign('msg',$this->messages);

        $smarty->display($config->root_path.'/app/calc/calcView.html');

    }
}