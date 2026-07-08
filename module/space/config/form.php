<?php
$config->space->form = new stdclass();

$config->space->form->create['name']        = array('type' => 'string', 'required' => true, 'filter' => 'trim');
$config->space->form->create['code']        = array('type' => 'string', 'required' => true, 'filter' => 'trim');
$config->space->form->create['manager']     = array('type' => 'array', 'required' => false, 'default' => array(), 'filter' => 'join');
$config->space->form->create['desc']        = array('type' => 'string', 'control' => 'editor', 'required' => false, 'default' => '', 'width' => 'full');
$config->space->form->create['acl']         = array('type' => 'string', 'required' => true, 'default' => 'open', 'filter' => 'trim');
$config->space->form->create['auth']        = array('type' => 'string', 'required' => true, 'default' => 'extend', 'filter' => 'trim');
$config->space->form->create['createdDate'] = array('type' => 'datetime', 'required' => false, 'default' => helper::now());

$config->space->form->edit = $config->space->form->create;
$config->space->form->edit['editedDate'] = array('type' => 'datetime', 'required' => false, 'default' => helper::now());
unset($config->space->form->edit['createdDate']);

$config->space->form->manageMembers = array();
$config->space->form->manageMembers['account']      = array('label' => $this->lang->space->account,        'type' => 'string', 'control' => array('control' => 'picker'), 'required' => false,  'width' => '100px', 'default' => '', 'filter'  => 'trim', 'base' => true);
$config->space->form->manageMembers['role']         = array('label' => $this->lang->space->role,           'type' => 'string', 'control' => array('control' => 'picker', 'required' => true), 'readonly' => true,  'width' => '100px', 'default' => 'member', 'filter'  => 'trim');
$config->space->form->manageMembers['group']        = array('label' => $this->lang->space->memberGroup,    'type' => 'array',  'control' => array('control' => 'picker', 'multiple' => true), 'required' => false, 'width' => '200px',  'default' => 0,  'options' => array());
$config->space->form->manageMembers['repo']         = array('label' => $this->lang->space->accessRepo,     'type' => 'array',  'control' => array('control' => 'picker', 'multiple' => true), 'tip' => $lang->space->notice->accessRepo, 'tipIcon' => 'help', 'required' => false, 'width' => '200px',  'default' => 0,  'options' => array());
//$config->space->form->manageMembers['artifactrepo'] = array('label' => $this->lang->space->accessArtifact, 'type' => 'array',  'control' => array('control' => 'picker', 'multiple' => true), 'tip' => $lang->space->notice->accessArtifact, 'tipIcon' => 'help', 'required' => false, 'width' => '200px',  'default' => 0,  'options' => array());
