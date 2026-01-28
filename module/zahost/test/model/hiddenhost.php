#!/usr/bin/env php
<?php

/**

title=测试 zahostModel::hiddenHost();
timeout=0
cid=19752

- 步骤1：空数据库情况，期望返回true @1
- 步骤2：有zahost类型未删除数据，期望返回false @0
- 步骤3：只有已删除zahost数据，期望返回true @1
- 步骤4：有多个zahost类型未删除数据，期望返回false @0
- 步骤5：只有非zahost类型数据，期望返回true @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$zahostTest = new zahostModelTest();

// 获取数据库访问对象
global $tester;

// 4. 强制要求：必须包含至少5个测试步骤

// 步骤1：清空所有数据，测试空数据库情况
$tester->dao->delete()->from(TABLE_ZAHOST)->exec();
r($zahostTest->hiddenHostTest()) && p() && e('1'); // 步骤1：空数据库情况，期望返回true

// 步骤2：插入zahost类型且未删除的数据
$tester->dao->insert(TABLE_ZAHOST)->data(array(
    'id' => 1001,
    'name' => 'testhost1',
    'type' => 'zahost',
    'extranet' => '192.168.1.101',
    'deleted' => '0',
    'status' => 'online'
))->exec();
r($zahostTest->hiddenHostTest()) && p() && e('0'); // 步骤2：有zahost类型未删除数据，期望返回false

// 步骤3：清空数据，只保留已删除的zahost
$tester->dao->delete()->from(TABLE_ZAHOST)->exec();
$tester->dao->insert(TABLE_ZAHOST)->data(array(
    'id' => 1002,
    'name' => 'deletedhost',
    'type' => 'zahost',
    'extranet' => '192.168.1.102',
    'deleted' => '1',
    'status' => 'offline'
))->exec();
r($zahostTest->hiddenHostTest()) && p() && e('1'); // 步骤3：只有已删除zahost数据，期望返回true

// 步骤4：清空数据，插入多个zahost类型且未删除的数据
$tester->dao->delete()->from(TABLE_ZAHOST)->exec();
$tester->dao->insert(TABLE_ZAHOST)->data(array(
    'id' => 1003,
    'name' => 'activehost1',
    'type' => 'zahost',
    'extranet' => '192.168.1.103',
    'deleted' => '0',
    'status' => 'online'
))->exec();
$tester->dao->insert(TABLE_ZAHOST)->data(array(
    'id' => 1004,
    'name' => 'activehost2',
    'type' => 'zahost',
    'extranet' => '192.168.1.104',
    'deleted' => '0',
    'status' => 'online'
))->exec();
r($zahostTest->hiddenHostTest()) && p() && e('0'); // 步骤4：有多个zahost类型未删除数据，期望返回false

// 步骤5：清空数据，只保留非zahost类型的主机
$tester->dao->delete()->from(TABLE_ZAHOST)->exec();
$tester->dao->insert(TABLE_ZAHOST)->data(array(
    'id' => 1005,
    'name' => 'vhost1',
    'type' => 'vhost',
    'extranet' => '192.168.1.105',
    'deleted' => '0',
    'status' => 'online'
))->exec();
r($zahostTest->hiddenHostTest()) && p() && e('1'); // 步骤5：只有非zahost类型数据，期望返回true