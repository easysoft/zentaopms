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

$config->port->dateFeilds     = ',estStarted,realStarted,deadline,openedDate,assignedDate,finishedDate,canceledDate,closedDate,lastEditedDate,';
$config->port->datetimeFeilds = '';
$config->port->sysLangField   = ',pri,status,type,mode,';
$config->port->sysDataField   = '';
$config->port->userField      = 'assignedTo,openedBy,finishedBy,canceledBy,closedBy,lastEditedBy,';
$config->port->initFunction   = 'title,control,required,';
