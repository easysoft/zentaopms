<?php
$config->provider->form = new stdclass();
$config->provider->form->create = array();
$config->provider->form->create['type']        = array('type' => 'string', 'required' => true, 'default' => 'GitLab');
$config->provider->form->create['name']        = array('type' => 'string', 'required' => true, 'default' => '', 'filter' => 'trim');
$config->provider->form->create['url']         = array('type' => 'string', 'required' => true, 'default' => '', 'filter' => 'trim');
$config->provider->form->create['account']     = array('type' => 'string', 'required' => false, 'default' => '', 'filter' => 'trim');
$config->provider->form->create['token']       = array('type' => 'string', 'required' => false, 'default' => '', 'filter' => 'trim');
$config->provider->form->create['createdDate'] = array('type' => 'string', 'required' => false, 'default' => helper::now());

$config->provider->form->edit = $config->provider->form->create;
$config->provider->form->create['editedDate'] = array('type' => 'string', 'required' => false, 'default' => helper::now());
unset($config->provider->form->edit['createdDate']);
