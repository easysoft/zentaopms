#!/usr/bin/env php
<?php

/**

title=测试 searchTao::initSession();
timeout=0
cid=0



*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

su('admin');

// 设置测试模块和字段
$module = 'bug';

$fields = array();
$fields['title']      = 'Bug名称';
$fields['keywords']   = '关键词';
$fields['steps']      = '重现步骤';
$fields['assignedTo'] = '指派给';
$fields['status']     = 'Bug状态';

// 配置字段参数
$title = new stdclass();
$title->operator = 'include';
$title->control  = 'input';
$title->value    = '';

$keywords = new stdClass();
$keywords->operator = 'include';
$keywords->control  = 'input';
$keywords->values   = '';

$steps = new stdClass();
$steps->operator = 'include';
$steps->control  = 'input';
$steps->values   = '';

$assignedTo = new stdClass();
$assignedTo->operator = '=';
$assignedTo->control  = 'select';
$assignedTo->values   = 'users';

$status = new stdClass();
$status->operator = '=';
$status->control  = 'select';
$status->values   = new stdclass();
$status->values->active   = '激活';
$status->values->resolved = '已解决';
$status->values->closed   = '已关闭';

$fieldParams = array();
$fieldParams['title']      = $title;
$fieldParams['keywords']   = $keywords;
$fieldParams['steps']      = $steps;
$fieldParams['assignedTo'] = $assignedTo;
$fieldParams['status']     = $status;

$search = new searchTaoTest();

r($search->initSessionTest($module, $fields, $fieldParams)) && p('0:field')    && e('title');
r($search->initSessionTest($module, $fields, $fieldParams)) && p('0:operator') && e('include');
r($search->initSessionTest($module, $fields, $fieldParams)) && p('0:andOr')    && e('and');
r($search->initSessionTest($module, $fields, $fieldParams)) && p('5:field')    && e('title');
r($search->initSessionTest($module, $fields, $fieldParams)) && p('3:field')    && e('assignedTo');
