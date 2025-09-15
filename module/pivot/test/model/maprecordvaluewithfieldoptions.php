#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::mapRecordValueWithFieldOptions();
timeout=0
cid=0

- 步骤1：正常情况第0条的status属性 @active
- 步骤2：边界值
 - 第0条的stage属性 @unittest
- 步骤3：异常输入 @0
- 步骤4：权限验证第0条的status属性 @active
- 步骤5：业务规则第0条的content属性 @Test Content

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('user');
$table->account->range('admin,user1,user2,user3,user4');
$table->realname->range('管理员,用户1,用户2,用户3,用户4');
$table->role->range('admin,user,dev,qa,pm');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：正常情况 - 测试基本字段映射功能
$records = array(
    (object)array('status' => 'active', 'priority' => '1', 'name' => 'Test Record 1'),
    (object)array('status' => 'closed', 'priority' => '2', 'name' => 'Test Record 2')
);

$fields = array(
    'status' => array('object' => 'user', 'field' => 'status', 'type' => 'status'),
    'priority' => array('object' => 'story', 'field' => 'pri', 'type' => 'pri'),
    'name' => array('object' => 'story', 'field' => 'title', 'type' => 'string')
);

r($pivotTest->mapRecordValueWithFieldOptionsTest($records, $fields, 'mysql')) && p('0:status') && e('active'); // 步骤1：正常情况

// 步骤2：多值字段处理 - 测试逗号分隔字段的处理
$records2 = array(
    (object)array('stage' => 'unittest,feature', 'assignedTo' => 'admin'),
);

$fields2 = array(
    'stage' => array('object' => 'testcase', 'field' => 'stage', 'type' => 'stage'),
    'assignedTo' => array('object' => 'user', 'field' => 'account', 'type' => 'user')
);

r($pivotTest->mapRecordValueWithFieldOptionsTest($records2, $fields2, 'mysql')) && p('0:stage') && e('unittest,feature'); // 步骤2：边界值

// 步骤3：空记录集处理 - 测试空输入的处理
$emptyRecords = array();
$emptyFields = array();

r($pivotTest->mapRecordValueWithFieldOptionsTest($emptyRecords, $emptyFields, 'mysql')) && p() && e('0'); // 步骤3：异常输入

// 步骤4：不匹配字段的过滤 - 测试字段过滤功能
$records4 = array(
    (object)array('status' => 'active', 'unknown_field' => 'value', 'priority' => '3')
);

$fields4 = array(
    'status' => array('object' => 'user', 'field' => 'status', 'type' => 'status')
);

r($pivotTest->mapRecordValueWithFieldOptionsTest($records4, $fields4, 'mysql')) && p('0:status') && e('active'); // 步骤4：权限验证

// 步骤5：特殊字符处理 - 测试HTML实体解码
$records5 = array(
    (object)array('content' => '&quot;Test Content&quot;', 'title' => 'Test&amp;Title')
);

$fields5 = array(
    'content' => array('object' => 'story', 'field' => 'spec', 'type' => 'string'),
    'title' => array('object' => 'story', 'field' => 'title', 'type' => 'string')
);

r($pivotTest->mapRecordValueWithFieldOptionsTest($records5, $fields5, 'mysql')) && p('0:content') && e('Test Content'); // 步骤5：业务规则