<?php
$config->api = new stdClass();
$config->api->createlib = new stdclass();
$config->api->createlib->requiredFields = 'name';

$config->api->editlib = new stdclass();
$config->api->editlib->requiredFields = 'name';

$config->api->struct = new stdClass();
$config->api->struct->requiredFields = 'name,params';

$config->api->create = new stdclass();
$config->api->create->requiredFields = 'lib,title,path,method,protocol';

$config->api->edit = new stdclass();
$config->api->edit->requiredFields = 'lib,title,path,method,protocol';

$config->api->editor = new stdclass();
$config->api->editor->createlib = ['id' => 'desc', 'tools' => 'simpleTools'];
$config->api->editor->create    = ['id' => 'desc', 'tools' => 'simpleTools'];
$config->api->editor->edit      = ['id' => 'desc', 'tools' => 'simpleTools'];
