#!/usr/bin/env php
<?php

/**

title=测试 metricModel::getPairsByIdList();
timeout=0
cid=17117

- 步骤1：正常获取用户信息属性1 @管理员
- 步骤2：正常获取产品信息属性1 @产品1
- 步骤3：传入空ID列表 @0
- 步骤4：传入不存在的账号 @0
- 步骤5：传入不存在的产品ID @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metric.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$userTable = zenData('user');
$userTable->id->range('1-5');
$userTable->account->range('admin,user1,user2,user3,user4');
$userTable->realname->range('管理员,用户1,用户2,用户3,用户4');
$userTable->deleted->range('0{5}');
$userTable->gen(5);

$productTable = zenData('product');
$productTable->id->range('1-3');
$productTable->name->range('产品1,产品2,产品3');
$productTable->deleted->range('0{3}');
$productTable->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$metricTest = new metricTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($metricTest->getPairsByIdListTest('user', array('admin', 'user1'))) && p('1') && e('管理员'); // 步骤1：正常获取用户信息
r($metricTest->getPairsByIdListTest('product', array(1, 2))) && p('1') && e('产品1'); // 步骤2：正常获取产品信息
r($metricTest->getPairsByIdListTest('user', array())) && p() && e('0'); // 步骤3：传入空ID列表
r($metricTest->getPairsByIdListTest('user', array('nonexist1', 'nonexist2'))) && p() && e('0'); // 步骤4：传入不存在的账号
r($metricTest->getPairsByIdListTest('product', array(999, 1000))) && p() && e('0'); // 步骤5：传入不存在的产品ID