#!/usr/bin/env php
<?php

/**

title=测试 fileModel::autoDelete();
timeout=0
cid=16492

- 步骤1：空字符串UID输入 @10
- 步骤2：正常UID但SESSION中无album数据 @10
- 步骤3：有album数据但部分图片已标记使用 @9
- 步骤4：有album数据但无used标记 @6
- 步骤5：多个UID混合测试，只影响指定UID @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/file.unittest.class.php';

$fileTable = zenData('file');
$fileTable->objectType->range('story,task,bug');
$fileTable->objectID->range('1-20');
$fileTable->pathname->range('test1.jpg,test2.png,test3.gif,test4.doc,test5.pdf');
$fileTable->title->range('测试图片1,测试图片2,测试图片3,测试文档1,测试文档2');
$fileTable->extension->range('jpg,png,gif,doc,pdf');
$fileTable->size->range('1024-102400');
$fileTable->addedBy->range('admin,user1,user2');
$fileTable->addedDate->range('`2023-01-01 00:00:00`-`2023-12-31 23:59:59`');
$fileTable->gen(10);

su('admin');

global $tester, $config;
$fileTest = new fileTest();

$debug = $config->debug;
$config->debug = 0;

// 清理SESSION状态
unset($_SESSION['album']);

$uid1 = 'test_uid_001';
$uid2 = 'test_uid_002';

r($fileTest->autoDeleteTest('')) && p() && e('10'); // 步骤1：空字符串UID输入
r($fileTest->autoDeleteTest($uid1)) && p() && e('10'); // 步骤2：正常UID但SESSION中无album数据

// 设置测试数据：uid1包含图片1,2,3，其中1,2已使用
$_SESSION['album'][$uid1] = array(1, 2, 3);
$_SESSION['album']['used'][$uid1] = array(1 => 1, 2 => 2);

r($fileTest->autoDeleteTest($uid1)) && p() && e('9'); // 步骤3：有album数据但部分图片已标记使用

// 重新设置数据：uid1包含图片4,5,6，无used标记
$_SESSION['album'][$uid1] = array(4, 5, 6);
unset($_SESSION['album']['used'][$uid1]);

r($fileTest->autoDeleteTest($uid1)) && p() && e('6'); // 步骤4：有album数据但无used标记

// 多UID测试：设置两个不同的UID
$_SESSION['album'][$uid1] = array(7, 8);
$_SESSION['album'][$uid2] = array(9, 10);
$_SESSION['album']['used'][$uid1] = array(7 => 7);

r($fileTest->autoDeleteTest($uid1)) && p() && e('5'); // 步骤5：多个UID混合测试，只影响指定UID

$config->debug = $debug;