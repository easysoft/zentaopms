<?php
$config->repobranchtype->form = new stdclass();

$config->repobranchtype->form->create['name']     = array('required' => true, 'type' => 'string', 'filter' => 'trim');
$config->repobranchtype->form->create['key']      = array('required' => true, 'type' => 'string', 'filter' => 'trim');
$config->repobranchtype->form->create['prefixes'] = array('required' => true, 'type' => 'array', 'default' => array());
$config->repobranchtype->form->create['desc']     = array('required' => false, 'type' => 'string', 'filter' => 'trim');

$config->repobranchtype->form->edit['name']     = array('required' => true, 'type' => 'string', 'filter' => 'trim');
$config->repobranchtype->form->edit['key']      = array('required' => true, 'type' => 'string', 'filter' => 'trim');
$config->repobranchtype->form->edit['prefixes'] = array('required' => true, 'type' => 'array', 'default' => array());
$config->repobranchtype->form->edit['desc']     = array('required' => false, 'type' => 'string', 'filter' => 'trim');
