<?php
$config->release->create->requiredFields = 'name,date';
$config->release->edit->requiredFields   = 'name,date';

$config->release->editor->create = array('id' => 'desc', 'tools' => 'simpleTools');
$config->release->editor->edit   = array('id' => 'desc', 'tools' => 'simpleTools');
