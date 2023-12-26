<?php
declare(strict_types=1);
global $lang, $app;

$config->zahost->form = new stdclass();

$config->zahost->form->create = array();
$config->zahost->form->create['vsoft']       = array('required' => true,  'type' => 'string', 'default' => '');
$config->zahost->form->create['hostType']    = array('required' => true,  'type' => 'string', 'default' => '');
$config->zahost->form->create['name']        = array('required' => true,  'type' => 'string', 'default' => '', 'filter' => 'trim');
$config->zahost->form->create['extranet']    = array('required' => true,  'type' => 'string', 'default' => '', 'filter' => 'trim');
$config->zahost->form->create['cpuCores']    = array('required' => true,  'type' => 'int',    'default' => 0);
$config->zahost->form->create['memory']      = array('required' => true,  'type' => 'float',  'default' => 0);
$config->zahost->form->create['diskSize']    = array('required' => true,  'type' => 'float',  'default' => 0);
$config->zahost->form->create['type']        = array('required' => false, 'type' => 'string', 'default' => 'zahost');
$config->zahost->form->create['status']      = array('required' => false, 'type' => 'string', 'default' => 'wait');
$config->zahost->form->create['createdBy']   = array('required' => false, 'type' => 'string', 'default' => isset($app->user->account) ? $app->user->account : '');
$config->zahost->form->create['createdDate'] = array('required' => false, 'type' => 'date',   'default' => helper::now());
$config->zahost->form->create['desc']        = array('required' => false, 'type' => 'string', 'default' => '');
$config->zahost->form->create['zap']         = array('required' => false, 'type' => 'string', 'default' => $config->zahost->defaultPort);
