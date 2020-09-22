<?php
$config->program = new stdclass();
$config->program->editor = new stdclass();
$config->program->editor->pgmcreate   = array('id' => 'desc',    'tools' => 'simpleTools');
$config->program->editor->pgmedit     = array('id' => 'desc',    'tools' => 'simpleTools');
$config->program->editor->pgmclose    = array('id' => 'comment', 'tools' => 'simpleTools');
$config->program->editor->pgmstart    = array('id' => 'comment', 'tools' => 'simpleTools');
$config->program->editor->pgmactivate = array('id' => 'comment', 'tools' => 'simpleTools');
$config->program->editor->pgmfinish   = array('id' => 'comment', 'tools' => 'simpleTools');
$config->program->editor->pgmsuspend  = array('id' => 'comment', 'tools' => 'simpleTools');

$config->program->editor->prjcreate   = array('id' => 'desc', 'tools' => 'simpleTools');
$config->program->editor->prjedit     = array('id' => 'desc', 'tools' => 'simpleTools');
$config->program->editor->prjclose    = array('id' => 'comment', 'tools' => 'simpleTools');
$config->program->editor->prjsuspend  = array('id' => 'comment', 'tools' => 'simpleTools');
$config->program->editor->prjstart    = array('id' => 'comment', 'tools' => 'simpleTools');
$config->program->editor->prjactivate = array('id' => 'comment', 'tools' => 'simpleTools');

$config->program->list = new stdclass();
$config->program->list->exportFields = 'id,name,code,template,category,status,begin,end,budget,PM,end,desc';

$config->program->PGMCreate = new stdclass();
$config->program->PGMEdit   = new stdclass();
$config->program->PGMCreate->requiredFields = 'name,code,begin,end';
$config->program->PGMEdit->requiredFields   = 'name,code,begin,end';

$config->program->PRJCreate = new stdclass();
$config->program->PRJEdit   = new stdclass();
$config->program->PRJCreate->requiredFields = 'name,code,begin,end';
$config->program->PRJEdit->requiredFields   = 'name,code,begin,end';
