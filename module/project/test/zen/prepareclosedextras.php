#!/usr/bin/env php
<?php

/**

title=测试 projectZen::prepareClosedExtras();
timeout=0
cid=0

- 步骤1：正常情况属性status @closed
- 步骤2：边界值属性id @0
- 步骤3：空POST数据属性status @closed
- 步骤4：负数项目ID属性id @-1
- 步骤5：HTML标签过滤
 - 属性status @closed
 - 属性closedBy @admin

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('project');
$table->id->range('1-10');
$table->name->range('项目{1-10}');
$table->status->range('wait{3},doing{4},closed{3}');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$projectTest = new projectzenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($projectTest->prepareClosedExtrasTest(1, (object)array('comment' => '关闭项目', 'status' => 'closed'))) && p('status') && e('closed'); // 步骤1：正常情况
r($projectTest->prepareClosedExtrasTest(0, (object)array('comment' => '测试', 'status' => 'closed'))) && p('id') && e(0); // 步骤2：边界值
r($projectTest->prepareClosedExtrasTest(1, (object)array())) && p('status') && e('closed'); // 步骤3：空POST数据
r($projectTest->prepareClosedExtrasTest(-1, (object)array('comment' => '测试', 'status' => 'closed'))) && p('id') && e(-1); // 步骤4：负数项目ID
r($projectTest->prepareClosedExtrasTest(1, (object)array('comment' => '<script>alert("test")</script>测试', 'status' => 'closed'))) && p('status,closedBy') && e('closed,admin'); // 步骤5：HTML标签过滤