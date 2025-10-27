#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createDefaultExecution();
timeout=0
cid=0

- 测试正常项目创建默认执行 @1
- 测试不同项目创建默认执行 @1
- 测试包含团队成员的项目创建 @1
- 测试项目不存在的情况处理 @0
- 测试空角色参数的项目创建 @1
- 测试空团队成员数组 @1
- 测试jiraProjectID为0的情况 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 准备测试数据
zenData('project')->loadYaml('project', false, 2)->gen(5);
zenData('user')->loadYaml('user', false, 2)->gen(10);
zenData('company')->loadYaml('company', false, 2)->gen(1);
zenData('config')->gen(0);
zenData('lang')->gen(0);
zenData('team')->gen(0);
zenData('action')->gen(0);
zenData('doclib')->gen(0);

su('admin');

$convertTest = new convertTest();

r($convertTest->createDefaultExecutionTest(1001, 1, array())) && p() && e('1'); // 测试正常项目创建默认执行
r($convertTest->createDefaultExecutionTest(1002, 2, array())) && p() && e('1'); // 测试不同项目创建默认执行
r($convertTest->createDefaultExecutionTest(1003, 3, array(1003 => array('user1', 'user2')))) && p() && e('1'); // 测试包含团队成员的项目创建
r($convertTest->createDefaultExecutionTest(1004, 999, array())) && p() && e('0'); // 测试项目不存在的情况处理
r($convertTest->createDefaultExecutionTest(1005, 4, array())) && p() && e('1'); // 测试空角色参数的项目创建
r($convertTest->createDefaultExecutionTest(1006, 5, array('1006' => array()))) && p() && e('1'); // 测试空团队成员数组
r($convertTest->createDefaultExecutionTest(0, 1, array())) && p() && e('1'); // 测试jiraProjectID为0的情况