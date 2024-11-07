<?php
declare(strict_types=1);

$config->admin->form = new stdclass();
$config->admin->form->safe = array();
$config->admin->form->safe['mode']                     = array('type' => 'int',    'required' => false, 'default' => 1);
$config->admin->form->safe['weak']                     = array('type' => 'string', 'required' => false);
$config->admin->form->safe['changeWeak']               = array('type' => 'int',    'required' => false, 'default' => 1);
$config->admin->form->safe['modifyPasswordFirstLogin'] = array('type' => 'int',    'required' => false, 'default' => 0);
$config->admin->form->safe['loginCaptcha']             = array('type' => 'int',    'required' => false, 'default' => 0);

$config->admin->form->sso = array();
$config->admin->form->sso['turnon']   = array('type' => 'int',    'required' => true, 'default' => 1);
$config->admin->form->sso['redirect'] = array('type' => 'int',    'required' => true, 'default' => 0);
$config->admin->form->sso['addr']     = array('type' => 'string', 'required' => false);
$config->admin->form->sso['code']     = array('type' => 'string', 'required' => false, 'filter' => 'trim');
$config->admin->form->sso['key']      = array('type' => 'string', 'required' => false, 'filter' => 'trim');

$config->admin->form->setmodule = array();
$config->admin->form->setmodule['module'] = array('type'=> 'array', 'required' => true, 'default' => array('myScore' => 0, 'productRoadmap' => 1, 'productTrack' => 1, 'productUR' => 1, 'otherDevops' => 1, 'otherKanban' => 1));

$config->admin->form->log = array();
$config->admin->form->log['days'] = array('type' => 'int', 'required' => true, 'default' => 30);

$config->admin->form->resetpwdsetting = array();
$config->admin->form->resetpwdsetting['resetPWDByMail'] = array('type' => 'int', 'required' => false, 'default' => 0);
