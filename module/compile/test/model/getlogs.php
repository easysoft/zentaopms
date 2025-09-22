#!/usr/bin/env php
<?php

/**

title=测试 compileModel::getLogs();
timeout=0
cid=0

- Jenkins日志获取，返回字符串类型 @1
- GitLab日志获取，返回字符串类型 @1
- 无效队列ID返回空字符串 @
- 空pipeline配置 @
- 空队列ID @
- 不支持的引擎类型 @
- GitLab缺少project配置 @

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/compile.unittest.class.php';

// 2. zendata数据准备
zenData('compile')->loadYaml('compile_getlogs', false, 2)->gen(10);
zenData('job')->loadYaml('job_getlogs', false, 2)->gen(6);
zenData('pipeline')->loadYaml('pipeline_getlogs', false, 2)->gen(6);
zenData('repo')->gen(3);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$compileTest = new compileTest();

// 5. 执行测试步骤（至少5个）

// 测试步骤1：Jenkins引擎正常获取日志
$compile = $tester->loadModel('compile')->getByID(1);
$job = $tester->loadModel('job')->getByID($compile->job);
$job->engine = 'jenkins';
r(is_string($compileTest->getLogsTest($job, $compile))) && p() && e('1'); // Jenkins日志获取，返回字符串类型

// 测试步骤2：GitLab引擎正常获取日志
$compile = $tester->loadModel('compile')->getByID(2);
$job = $tester->loadModel('job')->getByID($compile->job);
$job->engine = 'gitlab';
r(is_string($compileTest->getLogsTest($job, $compile))) && p() && e('1'); // GitLab日志获取，返回字符串类型

// 测试步骤3：无效队列ID的编译记录
$compile = $tester->loadModel('compile')->getByID(3);
$compile->queue = 0;
$job = $tester->loadModel('job')->getByID($compile->job);
$job->engine = 'jenkins';
r($compileTest->getLogsTest($job, $compile)) && p() && e(''); // 无效队列ID返回空字符串

// 测试步骤4：空的作业pipeline配置
$compile = $tester->loadModel('compile')->getByID(4);
$job = $tester->loadModel('job')->getByID($compile->job);
$job->engine = 'jenkins';
$job->pipeline = '';
r($compileTest->getLogsTest($job, $compile)) && p() && e(''); // 空pipeline配置

// 测试步骤5：队列ID为空的编译记录
$compile = $tester->loadModel('compile')->getByID(5);
$compile->queue = null;
$job = $tester->loadModel('job')->getByID($compile->job);
$job->engine = 'jenkins';
r($compileTest->getLogsTest($job, $compile)) && p() && e(''); // 空队列ID

// 测试步骤6：不支持的引擎类型
$compile = $tester->loadModel('compile')->getByID(6);
$job = $tester->loadModel('job')->getByID($compile->job);
$job->engine = 'unknown';
r($compileTest->getLogsTest($job, $compile)) && p() && e(''); // 不支持的引擎类型

// 测试步骤7：GitLab引擎但缺少project配置
$compile = $tester->loadModel('compile')->getByID(1);
$job = $tester->loadModel('job')->getByID($compile->job);
$job->engine = 'gitlab';
$job->pipeline = '{"reference": "master"}';
r($compileTest->getLogsTest($job, $compile)) && p() && e(''); // GitLab缺少project配置