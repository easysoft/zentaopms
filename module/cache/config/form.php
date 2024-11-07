<?php
$config->cache->form = new stdClass();
$config->cache->form->setting = array();
$config->cache->form->setting['enable']    = array('type' => 'int',    'default' => 0);
$config->cache->form->setting['driver']    = array('type' => 'string', 'default' => '');
$config->cache->form->setting['namespace'] = array('type' => 'string', 'default' => '');
$config->cache->form->setting['redis']     = array('type' => 'array',  'default' => array('host' => '', 'port' => 0, 'username' => '', 'password' => '', 'serializer' => ''));
