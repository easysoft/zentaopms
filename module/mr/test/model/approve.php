#!/usr/bin/env php
<?php

/**

title=测试 mrModel::approve();
timeout=0
cid=0

- 正常的合并请求审批拒绝属性message @保存成功
- 正常的合并请求审批通过属性message @保存成功
- 已合并状态的合并请求审批通过属性message @请勿重复操作

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mr.class.php';

zdTable('mr')->config('mr')->gen(3);
su('admin');

$mrModel = new mrTest();

$openedMR = 1;
$otherMR  = 2;

r($mrModel->approveTester($openedMR, 'reject')) && p('message') && e('保存成功');  // 正常的合并请求审批拒绝
r($mrModel->approveTester($openedMR, 'approve')) && p('message') && e('保存成功'); // 正常的合并请求审批通过

r($mrModel->approveTester($otherMR, 'approve')) && p('message') && e('请勿重复操作'); // 已合并状态的合并请求审批通过