<?php
$config->port = new stdclass();

$config->port->fieldList['title']    = '';
$config->port->fieldList['width']    = '';
$config->port->fieldList['required'] = 'no';
$config->port->fieldList['fixed']    = '';
$config->port->fieldList['control']  = 'input';
$config->port->fieldList['values']   = '';
$config->port->fieldList['class']    = '';
$config->port->fieldList['sort']     = '';
$config->port->fieldList['foreignKey'] = '';
$config->port->fieldList['foreignKeySource'] = array('module' => '', 'method' => '', 'params' => '', 'pairs' => '', 'sql' => '', 'lang' => '');

$config->port->initFunction   = 'title,control,required,';
$config->port->dateFeilds     = 'estStarted,realStarted,deadline,openedDate,assignedDate,finishedDate,canceledDate,closedDate,lastEditedDate,';
$config->port->datetimeFeilds = '';
$config->port->listFields     = '';
$config->port->sysLangFields  = ',pri,status,type,mode,severity,os,browser,resolution,confirmed,source,reviewResult,stage,change';
$config->port->sysDataFields  = 'execution,product,user';
$config->port->userFields     = 'assignedTo,openedBy,finishedBy,canceledBy,closedBy,lastEditedBy,';
$config->port->import = new stdClass();
$config->port->import->ignoreFields = explode(',', "mailto,openedBy,openedDate,assignedDate,finishedBy,finishedDate,canceledBy,,canceledDate,closedBy,closedDate,closedReason,lastEditedBy,lastEditedDate,files");
