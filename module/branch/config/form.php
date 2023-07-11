<?php
$config->branch->form = new stdclass();
$config->branch->form->team = new stdclass();

$config->branch->form->batchedit = common::formConfig('branch', 'batchedit');
$config->branch->form->batchedit['branchID'] = array('type' => 'int',    'required' => true, 'base' => true);
$config->branch->form->batchedit['name']     = array('type' => 'string', 'required' => true);
$config->branch->form->batchedit['desc']     = array('type' => 'string', 'required' => false, 'default' => '');
$config->branch->form->batchedit['status']   = array('type' => 'string', 'required' => false, 'default' => 'active');
