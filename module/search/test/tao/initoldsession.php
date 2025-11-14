#!/usr/bin/env php
<?php

/**

title=测试 searchTao::initOldSession();
timeout=0
cid=18330

- 步骤1：测试正常情况，验证session初始化
 - 属性field1 @title
 - 属性operator1 @include
- 步骤2：测试已存在session情况，验证不重复初始化
 - 属性field1 @title
 - 属性operator1 @include
- 步骤3：测试单个字段情况
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

// 2. zendata数据准备
// 为测试环境准备基础数据
$companyTable = zenData('company');
$companyTable->id->range('1');
$companyTable->name->range('Test Company');
$companyTable->admins->range(',admin,');
$companyTable->guest->range('1');
$companyTable->deleted->range('0');
$companyTable->gen(1);

$userTable = zenData('user');
$userTable->id->range('1');
$userTable->account->range('admin');
$userTable->realname->range('Admin');
$userTable->role->range('admin');
$userTable->deleted->range('0');
$userTable->gen(1);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$searchTest = new searchTest();

// 5. 测试步骤
r($searchTest->initOldSessionTest('bug', array('title' => 'Bug名称', 'keywords' => '关键词', 'assignedTo' => '指派给', 'status' => 'Bug状态'), array('title' => array('operator' => 'include'), 'keywords' => array('operator' => 'include'), 'assignedTo' => array('operator' => '='), 'status' => array('operator' => '=')), true)) && p('field1,operator1') && e('title,include'); // 步骤1：测试正常情况，验证session初始化
r($searchTest->initOldSessionTest('bug', array('title' => 'Bug名称', 'keywords' => '关键词', 'assignedTo' => '指派给', 'status' => 'Bug状态'), array('title' => array('operator' => 'include'), 'keywords' => array('operator' => 'include'), 'assignedTo' => array('operator' => '='), 'status' => array('operator' => '=')), false)) && p('field1,operator1') && e('title,include'); // 步骤2：测试已存在session情况，验证不重复初始化
r($searchTest->initOldSessionTest('task', array('name' => '任务名称'), array('name' => array('operator' => 'include')), true)) && p('field1,operator1') && e('name,include'); // 步骤3：测试单个字段情况
r($searchTest->initOldSessionTest('story', array('title' => '需求标题'), array('title' => array('operator' => 'include')), true)) && p('field1,operator1') && e('title,include'); // 步骤4：测试不同模块名称的session
r($searchTest->initOldSessionTest('user', array('account' => '用户名'), array('account' => array('operator' => '!=')), true)) && p('field1,operator1') && e('account,!='); // 步骤5：测试字段参数中的操作符设置