#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/personnel.class.php';
su('admin');

/**

title=测试 personnelModel->getAccessiblePersonnel();
cid=1
pid=1

测试取出结果集中的一个对象里的名字programID=1 >> 测试1
测试取出匹配的数量 >> 10
测试取出结果集中的一个对象里的名字programID=2 >> 开发11
测试取出匹配的数量 >> 10

*/

$personnel = new personnelTest('admin');

$programID = array();
$programID[0] = 1;
$programID[1] = 2;

$deptID = array();
$deptID[0] = 11;
$deptID[1] = 12;

$browseType = array();
$browseType[0] = 'all';
$browseType[1] = 'bysearch';

$queryID = array();
$browseType[0] = 1;

$getrealname1 = $personnel->getAccessiblePersonnelTest($programID[0], $deptID[0], $browseType[0], $browseType[0])[101];
$getnumber1   = count($personnel->getAccessiblePersonnelTest($programID[0], $deptID[0], $browseType[0], $browseType[0]));

$getrealname2 = $personnel->getAccessiblePersonnelTest($programID[1], $deptID[1], $browseType[0], $browseType[0])[111];
$getnumber2   = count($personnel->getAccessiblePersonnelTest($programID[1], $deptID[1], $browseType[0], $browseType[0]));

r($getrealname1) && p('realname') && e('测试1');  //测试取出结果集中的一个对象里的名字programID=1
r($getnumber1)   && p()           && e('10');     //测试取出匹配的数量
r($getrealname2) && p('realname') && e('开发11'); //测试取出结果集中的一个对象里的名字programID=2
r($getnumber2)   && p()           && e('10');     //测试取出匹配的数量