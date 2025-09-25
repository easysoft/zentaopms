#!/usr/bin/env php
<?php

/**

title=测试 searchTao::initSession();
timeout=0
cid=0

- 步骤1：测试基本字段初始化验证
 - 第0条的field属性 @title
 - 第0条的operator属性 @include
 - 第0条的andOr属性 @and
- 步骤2：测试字段循环重置机制第5条的field属性 @title
- 步骤3：测试不同字段的默认操作符
 - 第3条的field属性 @assignedTo
 - 第3条的operator属性 @=
- 步骤4：测试生成的总条目数量(groupItems*2+1) @7
- 步骤5：测试数组结构包含groupAndOr字段第6条的groupAndOr属性 @and

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

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

// 测试空字段情况
$emptyFields = array();
$emptyFieldParams = array();

// 测试单字段情况
$singleField = array('id' => 'ID');
$singleFieldParam = array();
$singleFieldParam['id'] = new stdClass();
$singleFieldParam['id']->operator = '=';
$singleFieldParam['id']->control = 'input';

$search = new searchTest();

r($search->initSessionTest($module, $fields, $fieldParams)) && p('0:field,operator,andOr') && e('title,include,and'); // 步骤1：测试基本字段初始化验证
r($search->initSessionTest($module, $fields, $fieldParams)) && p('5:field') && e('title'); // 步骤2：测试字段循环重置机制
r($search->initSessionTest($module, $fields, $fieldParams)) && p('3:field,operator') && e('assignedTo,='); // 步骤3：测试不同字段的默认操作符
r(count($search->initSessionTest($module, $fields, $fieldParams))) && p() && e('7'); // 步骤4：测试生成的总条目数量(groupItems*2+1)
r($search->initSessionTest($module, $fields, $fieldParams)) && p('6:groupAndOr') && e('and'); // 步骤5：测试数组结构包含groupAndOr字段