<?php
$config->todo = new stdclass();
$config->todo->batchCreate  = 8;

$config->todo->create = new stdclass();
$config->todo->edit   = new stdclass();
$config->todo->dates  = new stdclass();
$config->todo->times  = new stdclass();
$config->todo->create->requiredFields = 'name';
$config->todo->edit->requiredFields   = 'name';
$config->todo->dates->end             = 15;
$config->todo->times->begin           = 6;
$config->todo->times->end             = 23;
$config->todo->times->delta           = 10;

$config->todo->editor = new stdclass();
$config->todo->editor->create = array('id' => 'desc', 'tools' => 'simpleTools');
$config->todo->editor->edit   = array('id' => 'desc', 'tools' => 'simpleTools');

$config->todo->list = new stdclass();
$config->todo->list->exportFields = 'id, account, date, begin, end, type, idvalue, pri, name, desc, status, private'; 
