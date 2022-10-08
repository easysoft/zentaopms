<?php
$config->custom->requiredModules = array();
$config->custom->requiredModules[10] = 'project';
$config->custom->requiredModules[20] = 'story';
$config->custom->requiredModules[40] = 'task';
$config->custom->requiredModules[80] = 'doc';
$config->custom->requiredModules[85] = 'user';

$config->custom->fieldList['execution']['create'] = 'desc';
$config->custom->fieldList['execution']['edit']   = 'desc,PO,PM,QD,RD';
$config->custom->fieldList['story']['create']     = 'module,pri,estimate,keywords';
