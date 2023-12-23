<?php
$config->pivot = new stdclass();
$config->pivot->widthInput = 128;
$config->pivot->widthDate  = 248;
$config->pivot->recPerPage = 50;
$config->pivot->recPerPageList = array(1,5,10,15,20,25,30,35,40,45,50,100,200,500,1000,2000);

$config->pivot->fileType =  array('xlsx' => 'xlsx', 'xls' => 'xls', 'html' => 'html', 'mht' => 'mht');

$config->pivot->create = new stdclass();
$config->pivot->create->requiredFields = 'type,group';

$config->pivot->edit = new stdclass();
$config->pivot->edit->requiredFields = 'type,group';

$config->pivot->design = new stdclass();
$config->pivot->design->requiredFields = 'group';

$config->pivot->multiColumn = array('cluBarX' => 'yaxis', 'cluBarY' => 'yaxis', 'radar' => 'yaxis', 'line' => 'yaxis', 'stackedBar' => 'yaxis', 'stackedBarY' => 'yaxis');

$config->pivot->checkForm = array();
$config->pivot->checkForm['line']        = array('cantequal' => 'xaxis,yaxis');
$config->pivot->checkForm['cluBarX']     = array('cantequal' => 'xaxis,yaxis');
$config->pivot->checkForm['cluBarY']     = array('cantequal' => 'xaxis,yaxis');
$config->pivot->checkForm['radar']       = array('cantequal' => 'xaxis,yaxis');
$config->pivot->checkForm['stackedBar']  = array('cantequal' => 'xaxis,yaxis');
$config->pivot->checkForm['stackedBarY'] = array('cantequal' => 'xaxis,yaxis');
global $lang;
$config->pivot->settings = array();
$config->pivot->settings['cluBarX'] = array();
$config->pivot->settings['cluBarX']['xaxis']   = array();
$config->pivot->settings['cluBarX']['xaxis'][] = array('field' => 'xaxis', 'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->pivot->chooseField, 'col' => 4);

$config->pivot->settings['cluBarX']['yaxis']   = array();
$config->pivot->settings['cluBarX']['yaxis'][] = array('field' => 'yaxis', 'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->pivot->chooseField, 'col' => 2);
$config->pivot->settings['cluBarX']['yaxis'][] = array('field' => 'agg', 'type' => 'select', 'options' => 'aggList', 'required' => false, 'placeholder' => $lang->pivot->aggType, 'col' => 2);

$config->pivot->settings['cluBarY'] = array();
$config->pivot->settings['cluBarY']['xaxis']   = array();
$config->pivot->settings['cluBarY']['xaxis'][] = array('field' => 'xaxis', 'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->pivot->chooseField, 'col' => 4);

$config->pivot->settings['cluBarY']['yaxis']   = array();
$config->pivot->settings['cluBarY']['yaxis'][] = array('field' => 'yaxis', 'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->pivot->chooseField, 'col' => 2);
$config->pivot->settings['cluBarY']['yaxis'][] = array('field' => 'agg', 'type' => 'select', 'options' => 'aggList', 'required' => false, 'placeholder' => $lang->pivot->aggType, 'col' => 2);

$config->pivot->settings['stackedBarY'] = array();
$config->pivot->settings['stackedBarY']['xaxis']   = array();
$config->pivot->settings['stackedBarY']['xaxis'][] = array('field' => 'xaxis', 'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->pivot->chooseField, 'col' => 4);

$config->pivot->settings['stackedBarY']['yaxis']   = array();
$config->pivot->settings['stackedBarY']['yaxis'][] = array('field' => 'yaxis', 'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->pivot->chooseField, 'col' => 2);
$config->pivot->settings['stackedBarY']['yaxis'][] = array('field' => 'agg', 'type' => 'select', 'options' => 'aggList', 'required' => false, 'placeholder' => $lang->pivot->aggType, 'col' => 2);

$config->pivot->settings['line'] = array();
$config->pivot->settings['line']['xaxis']   = array();
$config->pivot->settings['line']['xaxis'][] = array('field' => 'xaxis', 'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->pivot->chooseField, 'col' => 4);

$config->pivot->settings['line']['yaxis']   = array();
$config->pivot->settings['line']['yaxis'][] = array('field' => 'yaxis', 'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->pivot->chooseField, 'col' => 2);
$config->pivot->settings['line']['yaxis'][] = array('field' => 'agg', 'type' => 'select', 'options' => 'aggList', 'required' => false, 'placeholder' => $lang->pivot->aggType, 'col' => 2);

$config->pivot->settings['pie'] = array();
$config->pivot->settings['pie']['group']   = array();
$config->pivot->settings['pie']['group'][] = array('field' => 'group', 'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->pivot->chooseField, 'col' => 2);

$config->pivot->settings['pie']['metric']   = array();
$config->pivot->settings['pie']['metric'][] = array('field' => 'metric',  'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->pivot->chooseField, 'col' => 2);

$config->pivot->settings['pie']['stat']   = array();
$config->pivot->settings['pie']['stat'][] = array('field' => 'agg', 'type' => 'select', 'options' => 'aggList', 'required' => false, 'placeholder' => $lang->pivot->aggType, 'col' => 2);

$config->pivot->settings['radar'] = array();
$config->pivot->settings['radar']['xaxis']   = array();
$config->pivot->settings['radar']['xaxis'][] = array('field' => 'xaxis', 'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->pivot->chooseField, 'col' => 4);

$config->pivot->settings['radar']['yaxis']   = array();
$config->pivot->settings['radar']['yaxis'][] = array('field' => 'yaxis', 'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->pivot->chooseField, 'col' => 2);
$config->pivot->settings['radar']['yaxis'][] = array('field' => 'agg', 'type' => 'select', 'options' => 'aggList', 'required' => false, 'placeholder' => $lang->pivot->aggType, 'col' => 2);

$config->pivot->settings['stackedBar'] = array();
$config->pivot->settings['stackedBar']['xaxis']   = array();
$config->pivot->settings['stackedBar']['xaxis'][] = array('field' => 'xaxis', 'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->pivot->chooseField, 'col' => 4);

$config->pivot->settings['stackedBar']['yaxis']   = array();
$config->pivot->settings['stackedBar']['yaxis'][] = array('field' => 'yaxis', 'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->pivot->chooseField, 'col' => 2);
$config->pivot->settings['stackedBar']['yaxis'][] = array('field' => 'agg', 'type' => 'select', 'options' => 'aggList', 'required' => false, 'placeholder' => $lang->pivot->aggType, 'col' => 2);

$config->pivot->settings['testingReport'] = array('field' => array('type' => 'td'));

$config->pivot->transTypes = array();
$config->pivot->transTypes['int']      = 'number';
$config->pivot->transTypes['float']    = 'number';
$config->pivot->transTypes['double']   = 'number';
$config->pivot->transTypes['datetime'] = 'date';
$config->pivot->transTypes['date']     = 'date';
