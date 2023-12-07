#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/search.class.php';

su('admin');

/**

title=测试 searchModel->initSession();
timeout=0
cid=1

- 测试title的值
 - 第0条的field属性 @title
 - 第0条的operator属性 @include
- 测试assignedTo的值
 - 第3条的field属性 @assignedTo
 - 第3条的operator属性 @=

*/

$module = 'bug';

$fields = array();
$fields['title']      = 'Bug名称';
$fields['keywords']   = '关键词';
$fields['steps']      = '重现步骤';
$fields['assignedTo'] = '指派给';
$fields['status']     = 'Bug状态';

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

$search = new searchTest();
r($search->initSessionTest($module, $fields, $fieldParams)) && p('0:field,operator') && e('title,include'); //测试title的值
r($search->initSessionTest($module, $fields, $fieldParams)) && p('3:field,operator') && e('assignedTo,='); //测试assignedTo的值