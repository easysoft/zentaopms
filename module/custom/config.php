<?php
$config->custom = new stdClass();

$config->custom->story = new stdClass();
$config->custom->story->fields['priList']          = '优先级';
$config->custom->story->fields['sourceList']       = '来源';
$config->custom->story->fields['reasonList']       = '关闭原因';
$config->custom->story->fields['reviewResultList'] = '评审结果';
$config->custom->story->fields['statusList']       = '状态';
$config->custom->story->fields['stageList']        = '阶段';
$config->custom->story->canAdd = 'reasonList,reviewResultList,sourceList,priList';

$config->custom->task     = '';
$config->custom->bug      = '';
$config->custom->testcase = '';
$config->custom->testtask = '';
$config->custom->todo     = '';
$config->custom->user     = '';
