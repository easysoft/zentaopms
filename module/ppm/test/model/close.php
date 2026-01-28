#!/usr/bin/env php
<?php

/**

title=测试 mrModel::close();
timeout=0
cid=17239

- 测试步骤1：正常打开状态的MR关闭操作属性message @已关闭合并请求。
- 测试步骤2：已关闭状态的MR重复关闭操作属性message @请勿重复操作
- 测试步骤3：验证成功关闭的结果状态属性result @success
- 测试步骤4：验证不同MR的关闭操作属性result @success
- 测试步骤5：验证另一个正常MR的关闭属性message @已关闭合并请求。
- 测试步骤6：验证已关闭MR的失败结果属性result @fail
- 测试步骤7：验证已关闭MR的重复操作属性message @请勿重复操作

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$mrTable = zenData('mr');
$mrTable->id->range('1-7');
$mrTable->hostID->range('1{7}');
$mrTable->sourceProject->range('42{7}');
$mrTable->sourceBranch->range('branch-08{7}');
$mrTable->targetProject->range('42{7}');
$mrTable->targetBranch->range('master{7}');
$mrTable->mriid->range('19-25');
$mrTable->title->range('Test MR 1,Test MR 2,Test MR 3,Test MR 4,Test MR 5,Test MR 6,Test MR 7');
$mrTable->status->range('opened{4},closed{3}');
$mrTable->mergeStatus->range('can_be_merged{7}');
$mrTable->repoID->range('1{7}');
$mrTable->jobID->range('0{7}');
$mrTable->compileID->range('0{7}');
$mrTable->createdBy->range('admin{7}');
$mrTable->assignee->range('admin{7}');
$mrTable->deleted->range('0{7}');
$mrTable->gen(7);
su('admin');

$mrTest = new mrModelTest();

r($mrTest->closeTester(1)) && p('message') && e('已关闭合并请求。');  // 测试步骤1：正常打开状态的MR关闭操作
r($mrTest->closeTester(5)) && p('message') && e('请勿重复操作');      // 测试步骤2：已关闭状态的MR重复关闭操作
r($mrTest->closeTester(2)) && p('result') && e('success');          // 测试步骤3：验证成功关闭的结果状态
r($mrTest->closeTester(3)) && p('result') && e('success');          // 测试步骤4：验证不同MR的关闭操作
r($mrTest->closeTester(4)) && p('message') && e('已关闭合并请求。');  // 测试步骤5：验证另一个正常MR的关闭
r($mrTest->closeTester(6)) && p('result') && e('fail');             // 测试步骤6：验证已关闭MR的失败结果
r($mrTest->closeTester(7)) && p('message') && e('请勿重复操作');      // 测试步骤7：验证已关闭MR的重复操作