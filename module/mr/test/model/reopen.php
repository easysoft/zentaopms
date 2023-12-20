#!/usr/bin/env php
<?php

/**

title=测试 mrModel::reopen();
timeout=0
cid=0

- 已创建状态的合并请求属性message @请勿重复操作
- 已关闭状态的合并请求属性message @已重新打开合并请求。

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mr.class.php';

$mr = zdTable('mr')->config('mr');
$mr->hostID->range('1');
$mr->sourceProject->range('3');
$mr->gen(3);
su('admin');

$mrModel = new mrTest();

$openedMR = 1;
$otherMR  = 3;

r($mrModel->reopenTester($openedMR)) && p('message') && e('请勿重复操作');         // 已创建状态的合并请求
r($mrModel->reopenTester($otherMR))  && p('message') && e('已重新打开合并请求。'); // 已关闭状态的合并请求