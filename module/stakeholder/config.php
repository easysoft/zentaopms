<?php
$config->stakeholder->create = new stdclass();
$config->stakeholder->create->requiredFields = 'user,name,company';
$config->stakeholder->expect = new stdclass();
$config->stakeholder->expect->requiredFields = 'expect,progress';

$config->stakeholder->editor = new stdclass();
$config->stakeholder->editor->create       = array('id' => 'nature,analysis,strategy', 'tools' => 'simpleTools');
$config->stakeholder->editor->edit         = array('id' => 'nature,analysis,strategy', 'tools' => 'simpleTools');
$config->stakeholder->editor->view         = array('id' => 'comment,lastComment', 'tools' => 'simpleTools');
$config->stakeholder->editor->communicate  = array('id' => 'comment', 'tools' => 'simpleTools');
$config->stakeholder->editor->expect       = array('id' => 'expect,progress', 'tools' => 'simpleTools');
$config->stakeholder->editor->createexpect = array('id' => 'expect,progress', 'tools' => 'simpleTools');
$config->stakeholder->editor->editexpect   = array('id' => 'expect,progress', 'tools' => 'simpleTools');
$config->stakeholder->editor->viewexpect   = array('id' => 'comment,lastComment', 'tools' => 'simpleTools');

global $lang;
$config->stakeholder->search['module'] = 'stakeholder';

$config->stakeholder->search['fields']['expect']      = $lang->stakeholder->expect;
$config->stakeholder->search['fields']['userID']      = $lang->stakeholder->common;
$config->stakeholder->search['fields']['createdDate'] = $lang->stakeholder->createdDate;
$config->stakeholder->search['fields']['progress']    = $lang->stakeholder->progress;

$config->stakeholder->search['params']['expect']      = array('operator' => 'include', 'control' => 'input', 'values' => '');
$config->stakeholder->search['params']['userID']      = array('operator' => '=', 'control' => 'select', 'values' => '');
$config->stakeholder->search['params']['progress']    = array('operator' => 'include', 'control' => 'input', 'values' => '');
$config->stakeholder->search['params']['createdDate'] = array('operator' => '=', 'control' => 'input', 'values' => '', 'class' => 'date');
