<?php
declare(strict_types=1);

$config->repo->form = new stdclass();

$config->repo->form->create = array();
$config->repo->form->create['product']        = array('required' => true,  'type' => 'array');
$config->repo->form->create['projects']       = array('required' => false, 'type' => 'array', 'default' => array());
$config->repo->form->create['SCM']            = array('required' => true,  'type' => 'string', 'filter' => 'trim');
$config->repo->form->create['serviceHost']    = array('required' => false, 'type' => 'int');
$config->repo->form->create['serviceProject'] = array('required' => false, 'type' => 'string', 'default' => '');
$config->repo->form->create['name']           = array('required' => true,  'type' => 'string', 'filter' => 'trim');
$config->repo->form->create['path']           = array('required' => false, 'type' => 'string', 'default' => '');
$config->repo->form->create['encoding']       = array('required' => true,  'type' => 'string');
$config->repo->form->create['client']         = array('required' => false, 'type' => 'string', 'default' => '');
$config->repo->form->create['account']        = array('required' => false, 'type' => 'string', 'default' => '');
$config->repo->form->create['password']       = array('required' => false, 'type' => 'string', 'default' => '');
$config->repo->form->create['encrypt']        = array('required' => false, 'type' => 'string', 'default' => '');
$config->repo->form->create['desc']           = array('required' => false, 'type' => 'string', 'default' => '');
