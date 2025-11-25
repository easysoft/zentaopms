#!/usr/bin/env php
<?php

/**

title=测试 executionZen::setUserMoreLink();
timeout=0
cid=16446

- 返回4个数组 @4
- 返回4个数组 @4
- 返回4个空数组 @4
- PM数组包含admin用户 @1
- PM数组有用户 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

// 准备测试数据
zenData('user')->gen(10);
zenData('project')->gen(5);
$execution = zenData('project');
$execution->id->range('6-10');
$execution->project->range('1-5');
$execution->type->range('sprint');
$execution->PM->range('admin,user1,user2,user3,user4');
$execution->PO->range('po1,po2,po3,admin,user1');
$execution->QD->range('qa1,qa2,qa3,qa4,user2');
$execution->RD->range('dev1,dev2,dev3,dev4,dev5');
$execution->gen(5);

su('admin');

$executionZenTest = new executionZenTest();

// 准备测试对象
$singleExecution = $executionZenTest->objectModel->fetchByID(6);
$executionArray = $executionZenTest->objectModel->dao->select('*')->from(TABLE_PROJECT)->where('type')->eq('sprint')->limit(3)->fetchAll();

// 测试步骤1：传入单个执行对象
$result1 = $executionZenTest->setUserMoreLinkTest($singleExecution);
r(count($result1)) && p() && e('4'); // 返回4个数组

// 测试步骤2：传入执行对象数组
$result2 = $executionZenTest->setUserMoreLinkTest($executionArray);
r(count($result2)) && p() && e('4'); // 返回4个数组

// 测试步骤3：传入null参数
$result3 = $executionZenTest->setUserMoreLinkTest(null);
r(count($result3)) && p() && e('4'); // 返回4个空数组

// 测试步骤4：测试返回的用户数组包含对应角色
$result4 = $executionZenTest->setUserMoreLinkTest($singleExecution);
r(isset($result4[0]['admin']) ? 1 : 0) && p() && e('1'); // PM数组包含admin用户

// 测试步骤5：测试多个执行对象用户去重
$multiExecution = $executionZenTest->objectModel->dao->select('*')->from(TABLE_PROJECT)->where('type')->eq('sprint')->fetchAll();
$result5 = $executionZenTest->setUserMoreLinkTest($multiExecution);
r(count($result5[0]) > 0 ? 1 : 0) && p() && e('1'); // PM数组有用户