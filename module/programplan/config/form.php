<?php
declare(strict_types = 1);

if(!isset($config->programplan->create)) $config->programplan->create = new stdclass;
$config->programplan->create->form       = array();
$config->programplan->create->formFields = array('planIDList', 'names', 'PM', 'percents', 'attributes', 'acl', 'milestone', 'begin', 'end', 'realBegan', 'realEnd', 'desc', 'orders', 'type');
foreach($config->programplan->create->formFields as $field) $config->programplan->create->form[$field] = array('required' => false, 'type' => 'array');
