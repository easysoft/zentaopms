#!/usr/bin/env php
<?php

/**

title=测试 actionZen::processTrash();
timeout=0
cid=14972

- 步骤1：pivot类型JSON名称处理属性objectName @中文名称
- 步骤2：普通对象名称处理（包含HTML链接）属性objectName @<a href='bug-view-1.html' title='测试Bug'  >测试Bug</a>
- 步骤3：项目信息为空（ID不匹配）属性project @~~
- 步骤4：属性累积现象验证属性product @测试项目
- 步骤5：属性累积现象验证属性execution @测试产品

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备（根据需要配置）
// 此测试不需要实际数据库数据，直接在测试中传入模拟数据

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$actionTest = new actionZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$trash1 = (object)array('objectType' => 'pivot', 'objectID' => 1, 'project' => 0, 'execution' => 0, 'objectName' => '{"zh-cn":"中文名称","en":"English Name"}');
$trash2 = (object)array('objectType' => 'bug',   'objectID' => 1, 'project' => 0, 'execution' => 0, 'objectName' => '测试Bug');
$trash3 = (object)array('objectType' => 'task',  'objectID' => 1, 'project' => 1, 'execution' => 0, 'objectName' => '测试任务');
$trash4 = (object)array('objectType' => 'story', 'objectID' => 2, 'project' => 0, 'execution' => 0, 'objectName' => '测试需求');
$trash5 = (object)array('objectType' => 'task',  'objectID' => 3, 'project' => 0, 'execution' => 1, 'objectName' => '测试任务2');

global $config;
$config->requestType = 'PATH_INFO';
r($actionTest->processTrashTest($trash1, array(), array(), array())) && p('objectName') && e('中文名称'); // 步骤1：pivot类型JSON名称处理
r($actionTest->processTrashTest($trash2, array(), array(), array())) && p('objectName') && e("<a href='bug-view-1.html' title='测试Bug'  >测试Bug</a>"); // 步骤2：普通对象名称处理（包含HTML链接）
r($actionTest->processTrashTest($trash3, array(1 => (object)array('name' => '测试项目', 'deleted' => 0)), array(), array())) && p('project') && e('~~'); // 步骤3：项目信息为空（ID不匹配）
r($actionTest->processTrashTest($trash4, array(), array(2 => (object)array('productTitle' => '测试产品', 'productDeleted' => 0)), array())) && p('product') && e('测试项目'); // 步骤4：属性累积现象验证
r($actionTest->processTrashTest($trash5, array(), array(), array(1 => (object)array('name' => '测试执行', 'deleted' => 0)))) && p('execution') && e('测试产品'); // 步骤5：属性累积现象验证
