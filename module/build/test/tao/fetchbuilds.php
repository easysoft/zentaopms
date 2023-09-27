#!/usr/bin/env php
<?php
/**

title=测试 buildModel->fetchBuilds();
timeout=0
cid=1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/build.class.php';

zdTable('build')->config('build')->gen(10);
zdTable('project')->config('execution')->gen(30);
zdTable('release')->config('release')->gen(5);
zdTable('product')->config('product')->gen(5);
zdTable('user')->gen(5);
su('admin');

$productIdList[0] = array();
$productIdList[1] = array(1);

$paramsList     = array('', 'hasdeleted', 'hasproject', 'singled');
$objectTypeList = array('', 'project', 'execution');
$objectIdList   = array(0, 11, 101);

$shadowsList[0] = array();
$shadowsList[1] = range(1, 5);

global $tester;
$tester->loadModel('build');
r($tester->build->fetchBuilds($productIdList[0], $paramsList[0], $objectIdList[0], $objectTypeList[0], $shadowsList[0])) && p('1:name') && e('版本1'); // 获取系统内所有的版本信息
r($tester->build->fetchBuilds($productIdList[1], $paramsList[0], $objectIdList[0], $objectTypeList[0], $shadowsList[0])) && p('2:name') && e('版本2'); // 获取产品1下的所有版本信息
r($tester->build->fetchBuilds($productIdList[0], $paramsList[1], $objectIdList[0], $objectTypeList[0], $shadowsList[0])) && p('3:name') && e('版本3'); // 获取系统内所有的包括版本信息
r($tester->build->fetchBuilds($productIdList[0], $paramsList[2], $objectIdList[0], $objectTypeList[0], $shadowsList[0])) && p('4:name') && e('版本4'); // 获取所有关联项目版本信息
r($tester->build->fetchBuilds($productIdList[0], $paramsList[3], $objectIdList[0], $objectTypeList[0], $shadowsList[0])) && p('5:name') && e('版本5'); // 获取所有关联执行版本信息
r($tester->build->fetchBuilds($productIdList[0], $paramsList[0], $objectIdList[1], $objectTypeList[1], $shadowsList[0])) && p('1:name') && e('版本1'); // 获取敏捷项目下的所有版本信息
r($tester->build->fetchBuilds($productIdList[0], $paramsList[0], $objectIdList[2], $objectTypeList[2], $shadowsList[0])) && p('1:name') && e('版本1'); // 获取执行下的所有版本信息
r($tester->build->fetchBuilds($productIdList[0], $paramsList[0], $objectIdList[0], $objectTypeList[0], $shadowsList[1])) && p('6:name') && e('版本6'); // 获取系统内除了版本1-5之外的版本信息
r($tester->build->fetchBuilds($productIdList[1], $paramsList[1], $objectIdList[0], $objectTypeList[0], $shadowsList[0])) && p('3:name') && e('版本3'); // 获取产品1下系统内所有的包括版本信息
r($tester->build->fetchBuilds($productIdList[1], $paramsList[2], $objectIdList[0], $objectTypeList[0], $shadowsList[0])) && p('3:name') && e('版本3'); // 获取产品1下所有关联项目版本信息
r($tester->build->fetchBuilds($productIdList[1], $paramsList[3], $objectIdList[0], $objectTypeList[0], $shadowsList[0])) && p('3:name') && e('版本3'); // 获取产品1下所有关联执行版本信息
r($tester->build->fetchBuilds($productIdList[1], $paramsList[0], $objectIdList[1], $objectTypeList[1], $shadowsList[0])) && p('1:name') && e('版本1'); // 获取产品1下敏捷项目下的所有版本信息
r($tester->build->fetchBuilds($productIdList[1], $paramsList[0], $objectIdList[2], $objectTypeList[2], $shadowsList[0])) && p('1:name') && e('版本1'); // 获取产品1下执行下的所有版本信息
r($tester->build->fetchBuilds($productIdList[1], $paramsList[0], $objectIdList[0], $objectTypeList[0], $shadowsList[1])) && p()         && e('0');     // 获取产品1下系统内除了版本1-5之外的版本信息
