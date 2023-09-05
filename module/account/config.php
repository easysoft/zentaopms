<?php
$config->account->create = new stdclass;
$config->account->create->requiredFields = 'name,account,provider';

$config->account->edit = new stdclass;
$config->account->edit->requiredFields = 'name,account,provider';

$config->account->actions = new stdclass();
$config->account->actions->view = array();
$config->account->actions->view['suffixActions'] = array('edit');