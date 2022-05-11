#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/compile.class.php';
su('admin');

/**

title=测试 compileModel->exec();
cid=1
pid=1

*/
$compile1 = new stdclass();
$compile1->id   = 2;
$compile1->name = '这是一个compile数据';
$compile1->job  = 3;

$compile2 = new stdclass();
$compile2->id   = 3;
$compile2->name = '这是一个compile数据';
$compile2->job  = 1;

$compile = new compileTest();

r($compile->execTest($compile2));
r($compile->execTest($compile1)) && p() && e('0'); //检测是否能执行编译
r($compile->execTest($compile2)) && p() && e('1'); //检测是否能执行编译
