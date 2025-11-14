#!/usr/bin/env php
<?php

/**

title=测试 executionZen::setUserMoreLink();
timeout=0
cid=16445

- 应返回4个用户列表数组 @4
- 应返回4个用户列表数组 @4
- 应返回4个数组（但内容为空） @4
- 应返回4个数组（但内容为空） @4
- 应返回4个用户列表数组，重复用户会被处理 @4

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

// 准备测试数据
zenData('user')->gen(10);
zenData('project')->gen(5);

// 用户登录
su('admin');

// 创建测试实例
$executionZenTest = new executionZenTest();

// 测试步骤1：传入单个执行对象，获取用户列表
$execution1 = new stdClass();
$execution1->PO = 'admin';
$execution1->PM = 'user1';
$execution1->QD = 'user2';
$execution1->RD = 'user3';

$result1 = $executionZenTest->setUserMoreLinkTest($execution1);
r(count($result1)) && p() && e(4); // 应返回4个用户列表数组

// 测试步骤2：传入执行对象数组，获取用户列表
$execution2 = new stdClass();
$execution2->PO = 'user4';
$execution2->PM = 'user5';
$execution2->QD = 'user6';
$execution2->RD = 'user7';

$executionArray = array($execution1, $execution2);
$result2 = $executionZenTest->setUserMoreLinkTest($executionArray);
r(count($result2)) && p() && e(4); // 应返回4个用户列表数组

// 测试步骤3：传入null参数，获取空用户列表
$result3 = $executionZenTest->setUserMoreLinkTest(null);
r(count($result3)) && p() && e(4); // 应返回4个数组（但内容为空）

// 测试步骤4：传入空数组，获取空用户列表
$result4 = $executionZenTest->setUserMoreLinkTest(array());
r(count($result4)) && p() && e(4); // 应返回4个数组（但内容为空）

// 测试步骤5：传入包含重复用户的执行对象数组
$execution3 = new stdClass();
$execution3->PO = 'admin'; // 重复用户
$execution3->PM = 'user1'; // 重复用户
$execution3->QD = 'user8';
$execution3->RD = 'user9';

$executionArrayDup = array($execution1, $execution3);
$result5 = $executionZenTest->setUserMoreLinkTest($executionArrayDup);
r(count($result5)) && p() && e(4); // 应返回4个用户列表数组，重复用户会被处理