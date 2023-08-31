<?php
$config->execution->form = new stdclass();

$config->execution->form->importTask = array();

$config->execution->form->importTask['taskIdList'] = array('type' => 'int', 'required' => false, 'base' => true);

$config->execution->form->setkanban = array();
$config->execution->form->setkanban['colWidth']     = array('type' => 'int',    'required' => false);
$config->execution->form->setkanban['heightType']   = array('type' => 'string', 'required' => false);
$config->execution->form->setkanban['displayCards'] = array('type' => 'int',    'required' => false);
$config->execution->form->setkanban['fluidBoard']   = array('type' => 'string', 'required' => false);
$config->execution->form->setkanban['minColWidth']  = array('type' => 'int',    'required' => false);
$config->execution->form->setkanban['maxColWidth']  = array('type' => 'int',    'required' => false);

$config->execution->form->fixfirst['estimate'] = array('type' => 'float', 'required' => false);
