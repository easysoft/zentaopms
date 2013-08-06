<?php
$config->customlang = new stdClass();

$config->customlang->story = new stdClass();
$config->customlang->story->fields['priList']          = '优先级';
$config->customlang->story->fields['sourceList']       = '来源';
$config->customlang->story->fields['reasonList']       = '关闭原因';
$config->customlang->story->fields['reviewResultList'] = '评审结果';
$config->customlang->story->fields['statusList']       = '状态';
$config->customlang->story->fields['stageList']        = '阶段';
$config->customlang->story->canAdd = 'reasonList,reviewResultList,sourceList,priList';

$config->customlang->task     = '';
$config->customlang->bug      = '';
$config->customlang->testcase = '';
$config->customlang->testtask = '';
$config->customlang->todo     = '';
$config->customlang->user     = '';

