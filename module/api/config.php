<?php
global $lang;

$config->api                            = new stdClass();
$config->api->createlib                 = new stdclass();
$config->api->createlib->requiredFields = 'name';

$config->api->create                 = new stdclass();
$config->api->create->requiredFields = 'lib,module,title,path,method,protocol';

$config->api->edit                 = new stdclass();
$config->api->edit->requiredFields = 'lib,module,title,path,method,protocol';

$config->api->editor            = new stdclass();
$config->api->editor->createlib = ['id' => 'desc', 'tools' => 'simpleTools'];
$config->api->editor->create    = ['id' => 'desc', 'tools' => 'simpleTools'];
$config->api->editor->edit      = ['id' => 'desc', 'tools' => 'simpleTools'];
