#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::isClickable();
timeout=0
cid=16808

- 步骤1：store类型，start动作，stopped状态 @1
- 步骤2：store类型，stop动作，running状态 @1
- 步骤3：store类型，visit动作，有域名 @1
- 步骤4：非store类型，visit动作 @1
- 步骤5：非store类型，edit动作 @1
- 步骤6：非store类型，bindUser动作，GitLab应用 @1
- 步骤7：store类型，start动作，creating状态（不能启动） @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
// isClickable方法不依赖数据库，只需要创建测试对象

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$instanceTest = new instanceModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($instanceTest->isClickableTest((object)array('type' => 'store', 'status' => 'stopped'), 'ajaxStart')) && p() && e('1'); // 步骤1：store类型，start动作，stopped状态
r($instanceTest->isClickableTest((object)array('type' => 'store', 'status' => 'running'), 'ajaxStop')) && p() && e('1'); // 步骤2：store类型，stop动作，running状态
r($instanceTest->isClickableTest((object)array('type' => 'store', 'status' => 'running', 'domain' => 'test.com'), 'visit')) && p() && e('1'); // 步骤3：store类型，visit动作，有域名
r($instanceTest->isClickableTest((object)array('type' => 'external'), 'visit')) && p() && e('1'); // 步骤4：非store类型，visit动作
r($instanceTest->isClickableTest((object)array('type' => 'external'), 'edit')) && p() && e('1'); // 步骤5：非store类型，edit动作
r($instanceTest->isClickableTest((object)array('type' => 'external', 'appName' => 'GitLab'), 'bindUser')) && p() && e('1'); // 步骤6：非store类型，bindUser动作，GitLab应用
r($instanceTest->isClickableTest((object)array('type' => 'store', 'status' => 'creating'), 'ajaxStart')) && p() && e('0'); // 步骤7：store类型，start动作，creating状态（不能启动）