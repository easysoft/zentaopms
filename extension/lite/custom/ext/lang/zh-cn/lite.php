<?php
$lang->custom->execution       = '看板';
$lang->custom->closedExecution = '已关闭' . $lang->custom->execution;
$lang->custom->notice->readOnlyOfExecution = "禁止修改后，已关闭{$lang->custom->execution}下的卡片、日志以及关联目标都禁止修改。";

$lang->custom->object = array();
$lang->custom->object['project']   = '项目';
$lang->custom->object['execution'] = $lang->custom->execution;
$lang->custom->object['story']     = $lang->SRCommon;
$lang->custom->object['task']      = '卡片';
$lang->custom->object['todo']      = '待办';
$lang->custom->object['user']      = '用户';
$lang->custom->object['block']     = '区块';

if($this->config->edition != 'open') $lang->custom->system = array('required', 'score', 'feedback');
if($this->config->edition != 'open') $lang->custom->system = array('required', 'score');
