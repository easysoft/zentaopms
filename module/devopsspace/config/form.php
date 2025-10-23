<?php
$config->devopsspace->form = new stdclass();

$config->devopsspace->form->create['name']        = array('type' => 'string', 'required' => true, 'filter' => 'trim');
$config->devopsspace->form->create['owner']       = array('type' => 'string', 'required' => true, 'filter' => 'trim');
$config->devopsspace->form->create['team']        = array('type' => 'array', 'required' => false, 'default' => array(), 'filter' => 'join');
$config->devopsspace->form->create['desc']        = array('type' => 'string', 'required' => false, 'default' => '', 'filter' => 'trim');
$config->devopsspace->form->create['acl']         = array('type' => 'string', 'required' => true, 'default' => 'open', 'filter' => 'trim');
$config->devopsspace->form->create['createdDate'] = array('type' => 'datetime', 'required' => false, 'default' => helper::now());
