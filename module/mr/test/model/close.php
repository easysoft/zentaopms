#!/usr/bin/env php
<?php

/**

title=测试 mrModel::close();
timeout=0
cid=0

- 正常的合并请求属性message @已关闭合并请求。
- 已关闭状态的合并请求属性message @请勿重复操作

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mr.class.php';

zdTable('mr')->config('mr')->gen(3);
su('admin');

$mrModel = new mrTest();

$openedMR = 1;
$otherMR  = 3;

r($mrModel->closeTester($openedMR)) && p('message') && e('已关闭合并请求。'); // 正常的合并请求
r($mrModel->closeTester($otherMR))  && p('message') && e('请勿重复操作');     // 已关闭状态的合并请求