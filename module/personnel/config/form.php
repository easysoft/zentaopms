<?php
declare(strict_types=1);
global $lang, $app;

$config->personnel->form = new stdclass();
$config->personnel->form->addWhitelist = array();
$config->personnel->form->addWhitelist['account'] = array('required' => false, 'type' => 'string', 'base' => true, 'default' => '');
