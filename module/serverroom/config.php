<?php
$config->serverroom->create = new stdclass();
$config->serverroom->create->requiredFields = 'name,line';
$config->serverroom->edit = new stdclass();
$config->serverroom->edit->requiredFields = 'name,line';

$config->serverroom->actions = new stdclass();
$config->serverroom->actions->view = array();
$config->serverroom->actions->view['suffixActions'] = array('edit');
