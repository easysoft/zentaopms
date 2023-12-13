<?php
global $app;
$config->gitlab->form = new stdclass();
$config->gitlab->form->create = array();
$config->gitlab->form->create['type']        = array('type' => 'string',   'required' => true,  'default' => 'gitlab');
$config->gitlab->form->create['name']        = array('type' => 'string',   'required' => true, 'default' => '', 'filter' => 'trim');
$config->gitlab->form->create['url']         = array('type' => 'string',   'required' => true, 'default' => '', 'filter' => 'trim');
$config->gitlab->form->create['token']       = array('type' => 'string',   'required' => true,  'default' => '', 'filter' => 'trim');
$config->gitlab->form->create['createdBy']   = array('type' => 'string',   'required' => false, 'default' => $app->user->account);
$config->gitlab->form->create['createdDate'] = array('type' => 'string', 'required' => false, 'default' => helper::now());
$config->gitlab->form->create['private']     = array('type' => 'string',   'required' => false, 'default' => uniqid());

$config->gitlab->form->edit = array();
$config->gitlab->form->edit['name']       = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->gitlab->form->edit['url']        = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->gitlab->form->edit['token']      = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->gitlab->form->edit['editedBy']   = array('type' => 'string', 'required' => false, 'default' => $app->user->account);
$config->gitlab->form->edit['editedDate'] = array('type' => 'string', 'required' => false, 'default' => helper::now());

$config->gitlab->form->user = new stdclass();

$config->gitlab->form->user->create = common::formConfig('gitlab', 'createUser');
$config->gitlab->form->user->create['account']          = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->user->create['name']             = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->user->create['username']         = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->user->create['email']            = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->user->create['password']         = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->user->create['password_repeat']  = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->user->create['projects_limit']   = array('type' => 'int', 'required' => false, 'default' => '100000');
$config->gitlab->form->user->create['can_create_group'] = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->user->create['external']         = array('type' => 'string', 'required' => false, 'default' => '');

$config->gitlab->form->user->edit = common::formConfig('gitlab', 'editUser');
$config->gitlab->form->user->edit['id']               = array('type' => 'int', 'required' => true);
$config->gitlab->form->user->edit['account']          = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->user->edit['name']             = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->user->edit['username']         = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->user->edit['email']            = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->user->edit['password']         = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->user->edit['password_repeat']  = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->user->edit['projects_limit']   = array('type' => 'int', 'required' => false, 'default' => '100000');
$config->gitlab->form->user->edit['can_create_group'] = array('type' => 'string', 'required' => false, 'default' => '0');
$config->gitlab->form->user->edit['external']         = array('type' => 'string', 'required' => false, 'default' => '0');

$config->gitlab->form->group = new stdclass();

$config->gitlab->form->group->create = common::formConfig('gitlab', 'createGroup');
$config->gitlab->form->group->create['name']                    = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->group->create['path']                    = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->group->create['description']             = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->group->create['visibility']              = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->group->create['request_access_enabled']  = array('type' => 'string', 'required' => false, 'default' => '0');
$config->gitlab->form->group->create['lfs_enabled']             = array('type' => 'string', 'required' => false, 'default' => '0');
$config->gitlab->form->group->create['project_creation_level']  = array('type' => 'string', 'required' => false, 'default' => 'developer');
$config->gitlab->form->group->create['subgroup_creation_level'] = array('type' => 'string', 'required' => false, 'default' => 'maintainer');

$config->gitlab->form->group->edit = common::formConfig('gitlab', 'editGroup');
$config->gitlab->form->group->edit['id']                      = array('type' => 'int', 'required' => true);
$config->gitlab->form->group->edit['name']                    = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->group->edit['path']                    = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->group->edit['description']             = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->group->edit['visibility']              = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->group->edit['request_access_enabled']  = array('type' => 'string', 'required' => false, 'default' => '0');
$config->gitlab->form->group->edit['lfs_enabled']             = array('type' => 'string', 'required' => false, 'default' => '0');
$config->gitlab->form->group->edit['project_creation_level']  = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->group->edit['subgroup_creation_level'] = array('type' => 'string', 'required' => false, 'default' => '');

$config->gitlab->form->project = new stdclass();

$config->gitlab->form->project->create = common::formConfig('gitlab', 'createProject');
$config->gitlab->form->project->create['name']         = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->project->create['namespace_id'] = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->project->create['path']         = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->project->create['description']  = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->project->create['visibility']   = array('type' => 'string', 'required' => false, 'default' => '');

$config->gitlab->form->project->edit = common::formConfig('gitlab', 'editProject');
$config->gitlab->form->project->edit['id']           = array('type' => 'int', 'required' => true);
$config->gitlab->form->project->edit['name']         = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->project->edit['description']  = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->project->edit['visibility']   = array('type' => 'string', 'required' => false, 'default' => '');

$config->gitlab->form->branch = new stdclass();

$config->gitlab->form->branch->create = common::formConfig('gitlab', 'createBranch');
$config->gitlab->form->branch->create['branch'] = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->branch->create['ref']    = array('type' => 'string', 'required' => false, 'default' => '');

$config->gitlab->form->tag = new stdclass();

$config->gitlab->form->tag->create = common::formConfig('gitlab', 'createTag');
$config->gitlab->form->tag->create['tag_name'] = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->tag->create['ref']      = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->tag->create['message']  = array('type' => 'string', 'required' => false, 'default' => '');
