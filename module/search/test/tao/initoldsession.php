#!/usr/bin/env php
<?php

/**

title=测试 searchTao::initOldSession();
timeout=0
cid=0

- 步骤1：测试正常情况，验证session初始化
 - 属性field1 @title
 - 属性operator1 @include
- 步骤2：测试已存在session情况，验证不重复初始化
 - 属性field3 @assignedTo
 - 属性operator3 @=
- 步骤3：测试空字段数组情况
 - 属性field1 @name
 - 属性operator1 @include
- 步骤4：测试不同模块名称的session
 - 属性field1 @title
 - 属性operator1 @include
- 步骤5：测试字段参数中的操作符设置
 - 属性field1 @account
 - 属性operator1 @!=

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$searchTest = new searchTest();

// 准备测试数据
$module = 'bug';
$fields = array();
$fields['title'] = 'Bug名称';
$fields['keywords'] = '关键词';
$fields['assignedTo'] = '指派给';
$fields['status'] = 'Bug状态';

$fieldParams = array();
$fieldParams['title'] = array('operator' => 'include', 'control' => 'input');
$fieldParams['keywords'] = array('operator' => 'include', 'control' => 'input');
$fieldParams['assignedTo'] = array('operator' => '=', 'control' => 'select');
$fieldParams['status'] = array('operator' => '=', 'control' => 'select');

// 5. 测试步骤
r($searchTest->initOldSessionTest($module, $fields, $fieldParams, true)) && p('field1,operator1') && e('title,include'); // 步骤1：测试正常情况，验证session初始化
r($searchTest->initOldSessionTest($module, $fields, $fieldParams, false)) && p('field3,operator3') && e('assignedTo,='); // 步骤2：测试已存在session情况，验证不重复初始化
r($searchTest->initOldSessionTest('task', array('name' => '任务名称'), array('name' => array('operator' => 'include')), true)) && p('field1,operator1') && e('name,include'); // 步骤3：测试空字段数组情况
r($searchTest->initOldSessionTest('story', array('title' => '需求标题'), array('title' => array('operator' => 'include')), true)) && p('field1,operator1') && e('title,include'); // 步骤4：测试不同模块名称的session
r($searchTest->initOldSessionTest('user', array('account' => '用户名'), array('account' => array('operator' => '!=')), true)) && p('field1,operator1') && e('account,!='); // 步骤5：测试字段参数中的操作符设置