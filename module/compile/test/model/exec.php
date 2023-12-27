#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/compile.class.php';

zdTable('compile')->gen(10);
zdTable('job')->gen(1);
zdTable('repo')->gen(1);
zdTable('pipeline')->gen(1);
su('admin');

/**

title=测试 compileModel->exec();
cid=1
pid=1

检测job不存在时是否能执行编译 >> 0
检测job存在但是->id不等于->job是否能执行编译 >> 1
检测job存在同时->id等于->job是否能执行编译 >> 1

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
