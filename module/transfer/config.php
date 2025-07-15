<?php
$config->transfer = new stdclass();

$config->transfer->fieldList['title']      = '';
$config->transfer->fieldList['name']       = '';
$config->transfer->fieldList['label']      = '';
$config->transfer->fieldList['width']      = '';
$config->transfer->fieldList['required']   = 'no';
$config->transfer->fieldList['fixed']      = '';
$config->transfer->fieldList['control']    = 'input';
$config->transfer->fieldList['values']     = '';
$config->transfer->fieldList['items']      = array();
$config->transfer->fieldList['class']      = '';
$config->transfer->fieldList['sort']       = '';
$config->transfer->fieldList['dataSource'] = array('module' => '', 'method' => '', 'params' => '', 'pairs' => '', 'lang' => '');

$config->transfer->initFunction   = 'title,control,required,';
$config->transfer->dateFields     = 'estStarted,realStarted,deadline,openedDate,assignedDate,finishedDate,canceledDate,closedDate,lastEditedDate,';
$config->transfer->datetimeFields = '';
$config->transfer->listFields     = '';
$config->transfer->sysLangFields  = ',pri,status,type,mode,severity,os,browser,resolution,confirmed,source,reviewResult,stage,change,category,injection,identify';
$config->transfer->sysDataFields  = 'project,execution,product,user';
$config->transfer->userFields     = 'assignedTo,openedBy,finishedBy,canceledBy,closedBy,lastEditedBy,lastRunner,resolvedBy,reviewedBy,mailto';
$config->transfer->textareaFields = 'spec,desc';

$config->transfer->import = new stdClass();

$config->transfer->dateMatch = '/[1-9]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])/';

$config->transfer->requiredFields = array('module', 'pri');
$config->transfer->actionModule   = array('task');

$config->transfer->lazyLoading     = false;
$config->transfer->showImportCount = 20;

$config->transfer->convertInt['branch']['getPairs']['productID']                = true;
$config->transfer->convertInt['bug']['getDatatableModules']['productID']        = true;
$config->transfer->convertInt['tree']['getOptionMenu']['libID']                 = true;
$config->transfer->convertInt['tree']['getOptionMenu']['rootID']                = true;
$config->transfer->convertInt['tree']['getOptionMenu']['caselib']               = true;
$config->transfer->convertInt['tree']['getTaskOptionMenu']['rootID']            = true;
$config->transfer->convertInt['story']['getExecutionStoryPairs']['executionID'] = true;
$config->transfer->convertInt['productplan']['getPairs']['productID']           = true;
