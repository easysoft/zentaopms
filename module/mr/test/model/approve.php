#!/usr/bin/env php
<?php

/**

title=测试 mrModel::approve();
timeout=0
cid=17236

- 步骤1：正常拒绝审批属性message @保存成功
- 步骤2：正常通过审批属性message @保存成功
- 步骤3：关闭状态MR审批属性message @请勿重复操作
- 步骤4：空操作类型属性message @请勿重复操作
- 步骤5：重复审批操作属性message @请勿重复操作
- 步骤6：带评论的拒绝审批属性message @保存成功
- 步骤7：验证审批历史记录属性comment @审批意见：代码质量良好，可以合并

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mr.unittest.class.php';

zenData('mr')->loadYaml('mr')->gen(10);
zenData('mrapproval')->gen(0);
su('admin');

$mrModel = new mrTest();

$openedMR     = 1;  // 开放状态的MR
$closedMR     = 3;  // 关闭状态的MR
$approvedMR   = 2;  // 已审批状态的MR
$invalidMR    = 999; // 不存在的MR
$comment      = '审批意见：代码质量良好，可以合并';

r($mrModel->approveTester($openedMR, 'reject', '')) && p('message') && e('保存成功');                     // 步骤1：正常拒绝审批
r($mrModel->approveTester($openedMR, 'approve', '')) && p('message') && e('保存成功');                    // 步骤2：正常通过审批
r($mrModel->approveTester($closedMR, 'approve', '')) && p('message') && e('请勿重复操作');                 // 步骤3：关闭状态MR审批
r($mrModel->approveTester($openedMR, '', '')) && p('message') && e('请勿重复操作');                       // 步骤4：空操作类型
r($mrModel->approveTester($approvedMR, 'approve', '')) && p('message') && e('请勿重复操作');               // 步骤5：重复审批操作
r($mrModel->approveTester($openedMR, 'reject', $comment)) && p('message') && e('保存成功');               // 步骤6：带评论的拒绝审批
r($mrModel->approveHistoryTester($openedMR)) && p('comment') && e('审批意见：代码质量良好，可以合并');      // 步骤7：验证审批历史记录