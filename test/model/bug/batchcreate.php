#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';
su('admin');

/**

title=bugModel->batchCreate();
cid=1
pid=1

测试正常批量创建bug1 >> 批量bug一,trunk,3,codeerror,3,3,1,101
测试正常批量创建bug2 >> 批量bug五,trunk,codeerror,3,3,1,101
测试正常批量创建bug3 >> 批量bug九,1,config,3,3,1,0
测试短时间内重复批量创建bug >> 0
测试异常创建bug >> 『影响版本』不能为空。

*/

$productID     = 1;

$title          = array('批量bug一','批量bug二','批量bug三');
$openedBuild    = array(array('trunk', '3'), array('trunk'), array('1'));
$type           = array('codeerror','ditto','config');
$severity       = array('3','2','1');
$normal_create1 = array('title' => $title, 'types' => $type, 'openedBuilds' => $openedBuild, 'severity' => $severity);

$title          = array('批量bug四','批量bug五','批量bug六');
$normal_create2 = array('title' => $title, 'types' => $type, 'openedBuilds' => $openedBuild, 'severity' => $severity);

$title          = array('批量bug七','批量bug八','批量bug九');
$normal_create3 = array('title' => $title, 'types' => $type, 'openedBuilds' => $openedBuild, 'severity' => $severity);

$title            = array('异常一','异常二','异常三');
$openedBuild      = array(array('trunk'), array(''), array('1'));
$exception_create = array('title' => $title, 'openedBuilds' => $openedBuild);

$bug = new bugTest();
r($bug->batchCreateObject($productID, $normal_create1))   && p('0:title,openedBuild,type,severity,pri,product,execution')    && e('批量bug一,trunk,3,codeerror,3,3,1,101');         // 测试正常批量创建bug1
r($bug->batchCreateObject($productID, $normal_create2))   && p('1:title,openedBuild,type,severity,pri,product,execution')    && e('批量bug五,trunk,codeerror,3,3,1,101');           // 测试正常批量创建bug2
r($bug->batchCreateObject($productID, $normal_create3))   && p('2:title,openedBuild,type,severity,pri,product,execution')    && e('批量bug九,1,config,3,3,1,0');                    // 测试正常批量创建bug3
r($bug->batchCreateObject($productID, $normal_create1))   && p()                                                             && e('0');                                             // 测试短时间内重复批量创建bug
r($bug->batchCreateObject($productID, $exception_create)) && p('message:0')                                                  && e("『影响版本』不能为空。"); // 测试异常创建bug

