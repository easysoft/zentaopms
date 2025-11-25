#!/usr/bin/env php
<?php

/**

title=测试 mrModel::reopen();
timeout=0
cid=17259

- 测试步骤1：已开启状态的MR重新打开属性message @请勿重复操作
- 测试步骤2：已关闭状态的MR重新打开，API调用失败属性message @失败
- 测试步骤3：已合并状态的MR重新打开属性message @已重新打开合并请求。
- 测试步骤4：锁定状态的MR重新打开，API调用失败属性message @失败
- 测试步骤5：重复对已开启MR操作验证结果属性result @fail

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mr.unittest.class.php';

$mr = zenData('mr');
$mr->id->range('1-10');
$mr->hostID->range('1');
$mr->mriid->range('100-109');
$mr->sourceProject->range('3');
$mr->targetProject->range('3');
$mr->status->range('opened{3},closed{3},merged{2},locked{1},cannot_be_merged{1}');
$mr->gen(10);

su('admin');

$mrModel = new mrTest();

$openedMR1 = 1;  // opened状态
$closedMR1 = 4;  // closed状态
$mergedMR1 = 7;  // merged状态
$lockedMR  = 9;  // locked状态
$deletedMR = 10; // deleted状态

r($mrModel->reopenTester($openedMR1)) && p('message') && e('请勿重复操作');         // 测试步骤1：已开启状态的MR重新打开
r($mrModel->reopenTester($closedMR1)) && p('message') && e('失败');              // 测试步骤2：已关闭状态的MR重新打开，API调用失败
r($mrModel->reopenTester($mergedMR1)) && p('message') && e('已重新打开合并请求。'); // 测试步骤3：已合并状态的MR重新打开
r($mrModel->reopenTester($lockedMR)) && p('message') && e('失败');               // 测试步骤4：锁定状态的MR重新打开，API调用失败
r($mrModel->reopenTester($openedMR1)) && p('result') && e('fail');               // 测试步骤5：重复对已开启MR操作验证结果