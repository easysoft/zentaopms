<?php
$config->testtask = new stdclass();
$config->testtask->create = new stdclass();
$config->testtask->edit   = new stdclass();
$config->testtask->create->requiredFields = 'project,build,begin,end,name';
$config->testtask->edit->requiredFields   = 'project,build,begin,end,name';

$config->testtask->editor = new stdclass();
$config->testtask->editor->create = array('id' => 'desc', 'tools' => 'simpleTools');
$config->testtask->editor->edit   = array('id' => 'desc', 'tools' => 'simpleTools');
