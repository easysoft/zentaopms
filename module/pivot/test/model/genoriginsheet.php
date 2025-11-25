#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::genOriginSheet();
timeout=0
cid=17370

- 步骤1：正常情况 @1
- 步骤2：测试钻取设置 @1
- 步骤3：测试空SQL @1
- 步骤4：测试无效过滤器 @1
- 步骤5：测试语言配置 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('user');
$table->id->range('1-5');
$table->account->range('admin,user1,user2,user3,user4');
$table->realname->range('管理员,用户1,用户2,用户3,用户4');
$table->role->range('admin{1},dev{2},qa{2}');
$table->dept->range('1{2},2{2},3{1}');
$table->deleted->range('0');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 5. 强制要求：必须包含至少5个测试步骤
$result1 = $pivotTest->genOriginSheetTest(
    array('account' => array('object' => 'user', 'field' => 'account', 'type' => 'string')),
    array(),
    'SELECT account FROM zt_user WHERE deleted = "0" LIMIT 5',
    array(),
    array(),
    'mysql'
);
r(count($result1) == 2) && p() && e('1'); // 步骤1：正常情况

$result2 = $pivotTest->genOriginSheetTest(
    array('account' => array('object' => 'user', 'field' => 'account', 'type' => 'string'), 'role' => array('object' => 'user', 'field' => 'role', 'type' => 'string')),
    array('drills' => array('account' => array(array('queryField' => 'account', 'field' => 'account')))),
    'SELECT account, role FROM zt_user WHERE deleted = "0" LIMIT 3',
    array(),
    array(),
    'mysql'
);
r(count($result2) == 2) && p() && e('1'); // 步骤2：测试钻取设置

$result3 = $pivotTest->genOriginSheetTest(
    array('id' => array('object' => 'user', 'field' => 'id', 'type' => 'number')),
    array(),
    'SELECT id FROM zt_user WHERE id = 0',
    array(),
    array(),
    'mysql'
);
r(count($result3) == 2) && p() && e('1'); // 步骤3：测试空SQL

$result4 = $pivotTest->genOriginSheetTest(
    array('account' => array('object' => 'user', 'field' => 'account', 'type' => 'string')),
    array(),
    'SELECT account FROM zt_user WHERE deleted = "0" LIMIT 2',
    false,
    array(),
    'mysql'
);
r(count($result4) == 2) && p() && e('1'); // 步骤4：测试无效过滤器

$result5 = $pivotTest->genOriginSheetTest(
    array('account' => array('object' => 'user', 'field' => 'account', 'type' => 'string')),
    array(),
    'SELECT account FROM zt_user WHERE deleted = "0" LIMIT 2',
    array(),
    array('account' => '用户名'),
    'mysql'
);
r(count($result5) == 2) && p() && e('1'); // 步骤5：测试语言配置