<?php
declare(strict_types=1);

$config->install->form = new stdclass();

$config->install->form->step2 = array();
$config->install->form->step2['dbDriver']    = array('type' => 'string', 'required' => true, 'default' => 'mysql');
$config->install->form->step2['timezone']    = array('type' => 'string', 'required' => true);
$config->install->form->step2['defaultLang'] = array('type' => 'string', 'required' => true);
$config->install->form->step2['dbHost']      = array('type' => 'string', 'required' => true);
$config->install->form->step2['dbPort']      = array('type' => 'string', 'required' => true);
$config->install->form->step2['dbEncoding']  = array('type' => 'string', 'required' => true);
$config->install->form->step2['dbUser']      = array('type' => 'string', 'required' => true);
$config->install->form->step2['dbPassword']  = array('type' => 'string', 'required' => true);
$config->install->form->step2['dbName']      = array('type' => 'string', 'required' => true);
$config->install->form->step2['dbPrefix']    = array('type' => 'string', 'required' => true);
$config->install->form->step2['clearDB']     = array('type' => 'int',    'required' => false, 'default' => 0);
