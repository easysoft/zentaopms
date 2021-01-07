<?php
$config->budget = new stdclass();
$config->budget->editor = new stdclass();
$config->budget->create = new stdclass();

$config->budget->editor->create = array('id' => 'desc', 'tools' => 'simpleTools');
$config->budget->editor->edit   = array('id' => 'desc', 'tools' => 'simpleTools');

$config->budget->create->requiredFields = 'stage,subject,name';
