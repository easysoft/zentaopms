<?php
$config->branch->form = new stdclass();
$config->branch->form->team = new stdclass();

$config->branch->form->batchedit = common::formConfig('branch', 'batchedit');
$config->branch->form->batchedit['branchID'] = array('type' => 'int',    'required' => true, 'base' => true);
$config->branch->form->batchedit['name']     = array('type' => 'string', 'required' => true);
$config->branch->form->batchedit['desc']     = array('type' => 'string', 'required' => false, 'default' => '');
$config->branch->form->batchedit['status']   = array('type' => 'string', 'required' => false, 'default' => 'active');

$config->branch->form->create['name']        = array('type' => 'string', 'required' => true,  'default' => '');
$config->branch->form->create['desc']        = array('type' => 'string', 'required' => false, 'default' => '');
$config->branch->form->create['status']      = array('type' => 'string', 'required' => false, 'default' => 'active');
$config->branch->form->create['createdDate'] = array('type' => 'string', 'required' => false, 'default' => helper::today());

$config->branch->form->edit['name']   = array('type' => 'string', 'required' => true,  'default' => '');
$config->branch->form->edit['desc']   = array('type' => 'string', 'required' => false, 'default' => '');
$config->branch->form->edit['status'] = array('type' => 'string', 'required' => true,  'default' => '');

$config->branch->form->mergebranch = $config->branch->form->create;
$config->branch->form->mergebranch['name']               = array('type' => 'string', 'required' => false, 'default' => '');
$config->branch->form->mergebranch['createBranch']       = array('type' => 'int',    'required' => false, 'default' => 0);
$config->branch->form->mergebranch['targetBranch']       = array('type' => 'int',    'required' => false, 'default' => 0);
$config->branch->form->mergebranch['mergedBranchIDList'] = array('type' => 'string', 'required' => false, 'default' => '');

$config->branch->form->sort['orderBy']  = array('type' => 'string', 'required' => false, 'default' => '');
$config->branch->form->sort['branches'] = array('type' => 'string', 'required' => false, 'default' => '');
