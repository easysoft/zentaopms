<?php
$config->devopsspace->form = new stdclass();

$config->devopsspace->form->create['name']        = array('type' => 'string', 'required' => true, 'filter' => 'trim');
$config->devopsspace->form->create['owner']       = array('type' => 'string', 'required' => true, 'filter' => 'trim');
$config->devopsspace->form->create['team']        = array('type' => 'array', 'required' => false, 'default' => array(), 'filter' => 'join');
$config->devopsspace->form->create['desc']        = array('type' => 'string', 'control' => 'editor', 'required' => false, 'default' => '', 'width' => 'full');
$config->devopsspace->form->create['acl']         = array('type' => 'string', 'required' => true, 'default' => 'open', 'filter' => 'trim');
$config->devopsspace->form->create['createdDate'] = array('type' => 'datetime', 'required' => false, 'default' => helper::now());

$config->devopsspace->form->edit = $config->devopsspace->form->create;
$config->devopsspace->form->edit['updatedDate'] = array('type' => 'datetime', 'required' => false, 'default' => helper::now());
unset($config->devopsspace->form->edit['createdDate']);
