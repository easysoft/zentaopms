#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/compile.unittest.class.php';

zenData('compile')->gen(10);
zenData('job')->loadYaml('job')->gen(1);
zenData('repo')->gen(1);
zenData('pipeline')->gen(1);
su('admin');

/**

title=测试 compileModel->exec();
timeout=0
cid=15744

- 检测job不存在时是否能执行编译 @0
- 检测job存在但是$compile->id不等于$compile->job是否能执行编译 @1
- 检测job存在同时$compile->id等于$compile->job是否能执行编译 @1
- 检测job为0时是否能执行编译 @0
- 检测job存在同时$compile->id不等于$compile->job是否能执行编译 @0

*/
$compile1 = new stdclass();
$compile1->id         = 2;
$compile1->name       = '这是一个compile数据';
$compile1->updateDate = NULL;
$compile1->job        = 3;

$compile2 = new stdclass();
$compile2->id         = 3;
$compile2->name       = '这是一个compile数据';
$compile2->updateDate = NULL;
$compile2->job        = 1;

$compile3 = new stdclass();
$compile3->id         = 1;
$compile3->name       = '这是一个compile数据';
$compile3->updateDate = NULL;
$compile3->job        = 1;

$compile = new compileTest();

r($compile->execTest($compile1)) && p() && e('0'); //检测job不存在时是否能执行编译
r($compile->execTest($compile2)) && p() && e('1'); //检测job存在但是$compile->id不等于$compile->job是否能执行编译
r($compile->execTest($compile3)) && p() && e('1'); //检测job存在同时$compile->id等于$compile->job是否能执行编译
$compile3->job = 0;
r($compile->execTest($compile3)) && p() && e('0'); //检测job为0时是否能执行编译
$compile3->id = 2;
r($compile->execTest($compile3)) && p() && e('0'); //检测job存在同时$compile->id不等于$compile->job是否能执行编译
