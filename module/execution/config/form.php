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

$config->execution->form->create['project']       = array('type' => 'int',    'required' => true,  'control' => 'select', 'default' => 0);
$config->execution->form->create['name']          = array('type' => 'string', 'required' => true,  'control' => 'text',   'default' => '', 'filter' => 'trim');
$config->execution->form->create['code']          = array('type' => 'string', 'required' => false, 'control' => 'text',   'default' => '', 'filter' => 'trim');
$config->execution->form->create['begin']         = array('type' => 'date',   'required' => true,  'control' => 'date',   'default' => null);
$config->execution->form->create['end']           = array('type' => 'date',   'required' => true,  'control' => 'date',   'default' => null);
$config->execution->form->create['days']          = array('type' => 'int',    'required' => false, 'control' => 'text',   'default' => 0);
$config->execution->form->create['lifetime']      = array('type' => 'string', 'required' => false, 'control' => 'text',   'default' => '');
$config->execution->form->create['attribute']     = array('type' => 'string', 'required' => false, 'control' => 'text',   'default' => '');
$config->execution->form->create['percent']       = array('type' => 'float',  'required' => false, 'control' => 'text',   'default' => 0);
$config->execution->form->create['products']      = array('type' => 'array',  'required' => false, 'control' => 'select', 'default' => array());
$config->execution->form->create['branch']        = array('type' => 'array',  'required' => false, 'control' => 'select', 'default' => array());
$config->execution->form->create['plans']         = array('type' => 'array',  'required' => false, 'control' => 'select', 'default' => array());
$config->execution->form->create['team']          = array('type' => 'string', 'required' => false, 'control' => 'text',   'default' => '');
$config->execution->form->create['status']        = array('type' => 'string', 'required' => false, 'control' => 'text',   'default' => 'wait');
$config->execution->form->create['PM']            = array('type' => 'string', 'required' => false, 'control' => 'select', 'default' => '');
$config->execution->form->create['PO']            = array('type' => 'string', 'required' => false, 'control' => 'select', 'default' => '');
$config->execution->form->create['QD']            = array('type' => 'string', 'required' => false, 'control' => 'select', 'default' => '');
$config->execution->form->create['RD']            = array('type' => 'string', 'required' => false, 'control' => 'select', 'default' => '');
$config->execution->form->create['desc']          = array('type' => 'string', 'required' => false, 'control' => 'editor', 'default' => '');
$config->execution->form->create['acl']           = array('type' => 'string', 'required' => false, 'control' => 'radio',  'default' => '');
$config->execution->form->create['whitelist']     = array('type' => 'array',  'required' => false, 'control' => 'select', 'default' => array(), 'filter' => 'join');
$config->execution->form->create['openedVersion'] = array('type' => 'string', 'required' => false, 'control' => 'text',   'default' => $config->version);
$config->execution->form->create['vision']        = array('type' => 'string', 'required' => false, 'control' => 'text',   'default' => $config->vision);

$config->execution->form->edit['products'] = array('type' => 'array', 'required' => false, 'default' => array());
$config->execution->form->edit['branch']   = array('type' => 'array', 'required' => false, 'default' => array());
$config->execution->form->edit['plans']    = array('type' => 'array', 'required' => false, 'default' => array());

$config->execution->form->importBug['id']         = array('type' => 'int',    'required' => false, 'default' => 0, 'base' => true);
$config->execution->form->importBug['pri']        = array('type' => 'int',    'required' => false, 'default' => 0);
$config->execution->form->importBug['estStarted'] = array('type' => 'string', 'required' => false, 'default' => null);
$config->execution->form->importBug['deadline']   = array('type' => 'string', 'required' => false, 'default' => null);
$config->execution->form->importBug['estimate']   = array('type' => 'float',  'required' => false, 'default' => 0);
$config->execution->form->importBug['assignedTo'] = array('type' => 'string', 'required' => false, 'default' => '');
