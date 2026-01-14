#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

/**

title=executionModel->getTaskCount();
cid=16393

- 传入空数组，检查返回值 @0
- 传入无子任务数组。 @2
- 传入一级子任务数组 @4
- 传入二级子任务 @5
- 删除其中一级子任务 @3

*/

$tasks = array();
$tasks[0] = new stdclass();
$tasks[0]->id   = 1;
$tasks[0]->name = 'test1';
$tasks[1] = new stdclass();
$tasks[1]->id   = 2;
$tasks[1]->name = 'test2';

global $tester;
$executionModel = $tester->loadModel('execution');

r($executionModel->getTaskCount(array()))  && p() && e('0'); //传入空数组，检查返回值
r($executionModel->getTaskCount($tasks))  && p() && e('2');  //传入无子任务数组。

$tasks[1]->children = array();
$tasks[1]->children[0] = new stdclass();
$tasks[1]->children[0]->id   = 3;
$tasks[1]->children[0]->name = 'test3';
$tasks[1]->children[1] = new stdclass();
$tasks[1]->children[1]->id   = 4;
$tasks[1]->children[1]->name = 'test4';
r($executionModel->getTaskCount($tasks))  && p() && e('4'); //传入一级子任务数组

$tasks[1]->children[1]->children = array();
$tasks[1]->children[1]->children[0] = new stdclass();
$tasks[1]->children[1]->children[0]->id = 5;
r($executionModel->getTaskCount($tasks))  && p() && e('5'); //传入二级子任务

unset($tasks[1]->children[1]);
r($executionModel->getTaskCount($tasks))  && p() && e('3'); //删除其中一级子任务
