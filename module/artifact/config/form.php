<?php
$config->artifact->form = new stdclass();

$config->artifact->form->create = array();
$config->artifact->form->create['name']        = array('type' => 'string', 'required' => true, 'default' => '', 'filter' => 'trim');
$config->artifact->form->create['createdDate'] = array('type' => 'string', 'required' => false, 'default' => helper::now());
