<?php
$config->pipeline->form = new stdclass();

$config->pipeline->form->create = array();
$config->pipeline->form->create['name']        = array('type' => 'string', 'required' => true, 'default' => '', 'filter' => 'trim');
$config->pipeline->form->create['desc']        = array('type' => 'string', 'required' => false, 'default' => '', 'control' => 'editor');
$config->pipeline->form->create['createdDate'] = array('type' => 'string', 'required' => false, 'default' => helper::now());
