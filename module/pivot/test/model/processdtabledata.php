#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::processDTableData();
timeout=0
cid=17418

- 步骤1：正常情况第0条的0属性 @1
- 步骤2：空数据数组 @0
- 步骤3：缺失字段第0条的2属性 @~~
- 步骤4：对象转换第0条的1属性 @test
- 步骤5：复杂数据第0条的0属性 @10

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($pivotTest->processDTableDataTest(array('id', 'name'), array(array('id' => 1, 'name' => 'test1')))) && p('0:0') && e('1'); // 步骤1：正常情况
r($pivotTest->processDTableDataTest(array('id', 'name'), array())) && p() && e('0'); // 步骤2：空数据数组
r($pivotTest->processDTableDataTest(array('id', 'name', 'email'), array(array('id' => 1, 'name' => 'test')))) && p('0:2') && e('~~'); // 步骤3：缺失字段
r($pivotTest->processDTableDataTest(array('id', 'name'), array((object)array('id' => 1, 'name' => 'test')))) && p('0:1') && e('test'); // 步骤4：对象转换
r($pivotTest->processDTableDataTest(array('user_id', 'user_name'), array(array('user_id' => 10, 'user_name' => 'admin', 'extra' => 'ignored')))) && p('0:0') && e('10'); // 步骤5：复杂数据