<?php
$config->testtask->create->requiredFields = 'project,build,begin,end,name';
$config->testtask->edit->requiredFields   = 'project,build,begin,end,name';

$config->testtask->editor->create = array('id' => 'desc', 'tools' => 'simpleTools');
$config->testtask->editor->edit   = array('id' => 'desc', 'tools' => 'simpleTools');
