#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printScrumListBlock();
timeout=0
cid=0

- 步骤1：正常情况执行printScrumListBlock方法 @1
- 步骤2：使用不同类型参数执行 @1
- 步骤3：使用不同计数参数执行 @1
- 步骤4：传入特殊参数验证安全性 @1
- 步骤5：验证方法执行后view数据设置正确 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('project');
$table->id->range('1-10');
$table->name->range('项目1,项目2,项目3,项目4,项目5,项目6,项目7,项目8,项目9,项目10');
$table->type->range('project{10}');
$table->status->range('wait{3},doing{5},done{2}');
$table->model->range('scrum{8},waterfall{2}');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($blockTest->printScrumListBlockTest((object)array('params' => (object)array()))) && p() && e('1'); // 步骤1：正常情况执行printScrumListBlock方法
r($blockTest->printScrumListBlockTest((object)array('params' => (object)array('type' => 'doing')))) && p() && e('1'); // 步骤2：使用不同类型参数执行
r($blockTest->printScrumListBlockTest((object)array('params' => (object)array('count' => 20)))) && p() && e('1'); // 步骤3：使用不同计数参数执行
r($blockTest->printScrumListBlockTest((object)array('params' => (object)array('type' => 'test<script>')))) && p() && e('1'); // 步骤4：传入特殊参数验证安全性
r($blockTest->printScrumListBlockTest((object)array('params' => (object)array('type' => 'undone', 'count' => 10)))) && p() && e('1'); // 步骤5：验证方法执行后view数据设置正确