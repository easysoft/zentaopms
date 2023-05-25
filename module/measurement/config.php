<?php
$config->measurement = new stdclass();
$config->measurement->editor = new stdclass();
$config->measurement->editor->createtemplate = array('id' => 'content', 'tools' => 'measurementTools');
$config->measurement->editor->edittemplate   = array('id' => 'content', 'tools' => 'measurementTools');

$config->measurement->createbasic = new stdclass();
$config->measurement->editbasic = new stdclass();
$config->measurement->createderivation = new stdclass();
$config->measurement->editderivation = new stdclass();

$config->measurement->createbasic->requiredFields = 'name,code,unit,definition';
$config->measurement->editbasic->requiredFields   = 'name,code,unit,definition';
$config->measurement->createderivation->requiredFields = 'name,purpose';
$config->measurement->editderivation->requiredFields = 'name,purpose';

$config->measurement->createtemplate = new stdclass();
$config->measurement->createtemplate->requiredFields = 'name';

$config->measurement->edittemplate = new stdclass();
$config->measurement->edittemplate->requiredFields = $config->measurement->createtemplate->requiredFields;

$config->measurement->sqlBlackList = "create,drop,backup,alter,insert,replace,update,delete,rename,do,truncate,load,handler,lock,unlock,grant,outfile,infile";
$config->measurement->sqlBlackFunc = "current_user,load_file,session_user,database,user,system_user";

$config->measurement->triggers = array();
$config->measurement->triggers[] = 'project.close';

$config->measurement->scopeParams = new stdclass();
$config->measurement->scopeParams->program = '$program int';
$config->measurement->scopeParams->product = '$program int, $product int';
$config->measurement->scopeParams->project = '$program int, $product int, $stage int';

global $lang;
$config->measurement->search['module']                = 'measurement';
$config->measurement->search['fields']['name']        = $lang->measurement->name;
$config->measurement->search['fields']['id']          = $lang->measurement->id;
$config->measurement->search['fields']['purpose']     = $lang->measurement->purpose;
$config->measurement->search['fields']['scope']       = $lang->measurement->scope;
$config->measurement->search['fields']['object']      = $lang->measurement->object;
$config->measurement->search['fields']['code']        = $lang->measurement->code;
$config->measurement->search['fields']['unit']        = $lang->measurement->unit;
$config->measurement->search['fields']['definition']  = $lang->measurement->definition;
$config->measurement->search['fields']['collectType'] = $lang->measurement->collectType;
$config->measurement->search['fields']['createdBy']   = $lang->measurement->createdBy;

$config->measurement->search['params']['name']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->measurement->search['params']['id']          = array('operator' => '=', 'control' => 'input',  'values' => '');
$config->measurement->search['params']['purpose']     = array('operator' => '=', 'control' => 'select',  'values' => $lang->measurement->purposeList);
$config->measurement->search['params']['scope']       = array('operator' => '=', 'control' => 'select',  'values' => $lang->measurement->scopeList);
$config->measurement->search['params']['object']      = array('operator' => '=', 'control' => 'select',  'values' => $lang->measurement->objectList);
$config->measurement->search['params']['code']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->measurement->search['params']['unit']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->measurement->search['params']['definition']  = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->measurement->search['params']['collectType'] = array('operator' => '=', 'control' => 'select',  'values' => $lang->measurement->collectTypeList);
$config->measurement->search['params']['createdBy']   = array('operator' => '=', 'control' => 'select',  'values' => 'users');
