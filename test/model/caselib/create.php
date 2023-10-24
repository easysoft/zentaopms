#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/caselib.class.php';
su('admin');

/**

title=测试 caselibModel->create();
cid=1
pid=1

测试名称是空时候添加 >> 『名称』不能为空。
测试添加的名称信息 >> 测试用例库名称
测试添加的描述信息 >> 测试用例库描述
测试名称是空时候添加 >> 『名称』已经有『测试用例库名称』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。

*/

$caselib = new caselibTest();

$lib       = new stdClass();
$lib->name = "";
$result1   = $caselib->createTest($lib);

$lib       = new stdClass();
$lib->name = '测试用例库名称';
$lib->desc = '测试用例库描述';
$result2   = $caselib->createTest($lib);

$lib       = new stdClass();
$lib->name = '测试用例库名称';
$result3   = $caselib->createTest($lib);

r($result1) && p('name:0') && e('『名称』不能为空。'); //测试名称是空时候添加
r($result2) && p('name')   && e('测试用例库名称');     //测试添加的名称信息
r($result2) && p('desc')   && e('测试用例库描述');     //测试添加的描述信息
r($result3) && p('name:0') && e('『名称』已经有『测试用例库名称』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。'); //测试名称是空时候添加

