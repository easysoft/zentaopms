<?php
$config->execution->form = new stdclass();

$config->execution->form->importTask['taskIdList'] = array('type' => 'array', 'required' => false, 'default' => array());

$config->execution->form->setkanban = array();
$config->execution->form->setkanban['colWidth']     = array('type' => 'int',    'required' => false);
$config->execution->form->setkanban['heightType']   = array('type' => 'string', 'required' => false);
$config->execution->form->setkanban['displayCards'] = array('type' => 'int',    'required' => false);
$config->execution->form->setkanban['fluidBoard']   = array('type' => 'string', 'required' => false);
$config->execution->form->setkanban['minColWidth']  = array('type' => 'int',    'required' => false);
$config->execution->form->setkanban['maxColWidth']  = array('type' => 'int',    'required' => false);

$config->execution->form->fixfirst['estimate'] = array('type' => 'float', 'required' => false);

$config->execution->form->managemembers['account'] = array('type' => 'string', 'required' => false, 'default' => '', 'base' => true);
$config->execution->form->managemembers['role']    = array('type' => 'string', 'required' => false, 'default' => '');
$config->execution->form->managemembers['days']    = array('type' => 'int', 'required' => false, 'default' => 0);
$config->execution->form->managemembers['hours']   = array('type' => 'float', 'required' => false, 'default' => 0);
$config->execution->form->managemembers['limited'] = array('type' => 'string', 'required' => false, 'default' => 'no');
$config->execution->form->managemembers['type']    = array('type' => 'string', 'required' => false, 'default' => 'execution');
$config->execution->form->managemembers['root']    = array('type' => 'int', 'required' => false, 'default' => 0);

$config->execution->form->manageproducts['products'] = array('type' => 'array', 'required' => false, 'default' => array());
$config->execution->form->manageproducts['branch']   = array('type' => 'array', 'required' => false, 'default' => array());

$config->execution->form->create['products'] = array('type' => 'array', 'required' => false, 'default' => array());
$config->execution->form->create['branch']   = array('type' => 'array', 'required' => false, 'default' => array());
$config->execution->form->create['plans']    = array('type' => 'array', 'required' => false, 'default' => array());

$config->execution->form->edit['products'] = array('type' => 'array', 'required' => false, 'default' => array());
$config->execution->form->edit['branch']   = array('type' => 'array', 'required' => false, 'default' => array());
$config->execution->form->edit['plans']    = array('type' => 'array', 'required' => false, 'default' => array());

$config->execution->form->importBug['id']         = array('type' => 'int',    'required' => false, 'default' => 0, 'base' => true);
$config->execution->form->importBug['pri']        = array('type' => 'int',    'required' => false, 'default' => 0);
$config->execution->form->importBug['estStarted'] = array('type' => 'string', 'required' => false, 'default' => null);
$config->execution->form->importBug['deadline']   = array('type' => 'string', 'required' => false, 'default' => null);
$config->execution->form->importBug['estimate']   = array('type' => 'float',  'required' => false, 'default' => 0);
$config->execution->form->importBug['assignedTo'] = array('type' => 'string', 'required' => false, 'default' => '');
