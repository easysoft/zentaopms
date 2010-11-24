<?php
$config->release->create->requiredFields = 'name,build,date';
$config->release->edit->requiredFields   = 'name,build,date';

$config->release->editor->create = array('id' => 'desc', 'tools' => 'simpleTools');
$config->release->editor->edit   = array('id' => 'desc', 'tools' => 'simpleTools');
