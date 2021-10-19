<?php
$config->api = new stdClass();

$config->api->createlib = new stdclass();
$config->api->createlib->requiredFields = 'name';

$config->api->editlib = new stdclass();
$config->api->editlib->requiredFields = 'name';

$config->api->struct = new stdClass();
$config->api->struct->requiredFields = 'name,params';

$config->api->create = new stdclass();
$config->api->create->requiredFields = 'lib,title,path';

$config->api->edit = new stdclass();
$config->api->edit->requiredFields = 'lib,title,path';

$config->api->createrelease = new stdclass();
$config->api->createrelease->requiredFields = 'version';

$config->api->editor = new stdclass();
$config->api->editor->createlib     = array('id' => 'desc', 'tools' => 'simpleTools');
$config->api->editor->editlib       = array('id' => 'desc', 'tools' => 'simpleTools');
$config->api->editor->create        = array('id' => 'desc', 'tools' => 'simpleTools');
$config->api->editor->edit          = array('id' => 'desc', 'tools' => 'simpleTools');
$config->api->editor->createRelease = array('id' => 'desc', 'tools' => 'simpleTools');
$config->api->editor->createstruct  = array('id' => 'desc', 'tools' => 'simpleTools');
$config->api->editor->editstruct    = array('id' => 'desc', 'tools' => 'simpleTools');
