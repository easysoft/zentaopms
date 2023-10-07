#!/usr/bin/env php
<?php
/**

title=测试 buildModel->getLast();
timeout=0
cid=1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/build.class.php';

zdTable('project')->config('execution')->gen(30);
zdTable('build')->config('build')->gen(30);
zdTable('user')->gen(5);
su('admin');

$projectIdList   = array(0, 11, 60, 100);
$executionIdList = array(101, 106, 124);

global $tester;
$tester->loadModel('build');
r($tester->build->getLast($executionIdList[0], $projectIdList[0])) && p('name') && e('版本10'); // 测试项目ID为空、执行ID=101时，获取最近一次创建的版本息
r($tester->build->getLast($executionIdList[0], $projectIdList[1])) && p('name') && e('版本1'); // 测试项目ID正确、执行ID=101时，获取最近一次创建的版本息
r($tester->build->getLast($executionIdList[0], $projectIdList[2])) && p('name') && e('版本10'); // 测试项目ID错误、执行ID=101时，获取最近一次创建的版本息
r($tester->build->getLast($executionIdList[1], $projectIdList[0])) && p('name') && e('版本20'); // 测试项目ID为空、执行ID=106时，获取最近一次创建的版本息
r($tester->build->getLast($executionIdList[1], $projectIdList[1])) && p('name') && e('版本29');      // 测试项目ID错误、执行ID=106时，获取最近一次创建的版本息
r($tester->build->getLast($executionIdList[1], $projectIdList[2])) && p('name') && e('版本2'); // 测试项目ID正确、执行ID=106时，获取最近一次创建的版本息
r($tester->build->getLast($executionIdList[2], $projectIdList[0])) && p('name') && e('版本4'); // 测试项目ID为空、执行ID=124时，获取最近一次创建的版本息
r($tester->build->getLast($executionIdList[2], $projectIdList[1])) && p('name') && e('版本13');      // 测试项目ID错误、执行ID=124时，获取最近一次创建的版本息
r($tester->build->getLast($executionIdList[2], $projectIdList[3])) && p('name') && e('版本4'); // 测试项目ID正确、执行ID=124时，获取最近一次创建的版本息
