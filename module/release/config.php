<?php
$config->release = new stdclass();
$config->release->create = new stdclass();
$config->release->edit   = new stdclass();
$config->release->create->requiredFields = 'name,date';
$config->release->edit->requiredFields   = 'name,date';

$config->release->editor = new stdclass();
$config->release->editor->create = array('id' => 'desc', 'tools' => 'simpleTools');
$config->release->editor->edit   = array('id' => 'desc', 'tools' => 'simpleTools');
