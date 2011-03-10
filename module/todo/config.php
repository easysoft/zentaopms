<?php
$config->todo->create->requiredFields = 'name';
$config->todo->edit->requiredFields   = 'name';
$config->todo->dates->end             = 15;
$config->todo->times->begin           = 9;
$config->todo->times->end             = 22;
$config->todo->times->delta           = 15;

$config->todo->editor->create = array('id' => 'desc', 'tools' => 'simpleTools');
$config->todo->editor->edit   = array('id' => 'desc', 'tools' => 'simpleTools');
