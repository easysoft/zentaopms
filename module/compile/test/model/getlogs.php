#!/usr/bin/env php
<?php

/**

title=测试 compileModel::getLogs();
timeout=0
cid=15750

- 执行compileTest模块的getLogsTest方法  @1
- 执行compileTest模块的getLogsTest方法  @1
- 执行compileTest模块的getLogsTest方法  @
- 执行compileTest模块的getLogsTest方法  @
- 执行compileTest模块的getLogsTest方法  @

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
ob_start();
zenData('compile')->loadYaml('compile_getlogs', false, 2)->gen(10);
zenData('job')->loadYaml('job_getlogs', false, 2)->gen(6);
zenData('pipeline')->loadYaml('pipeline_getlogs', false, 2)->gen(6);
zenData('repo')->gen(3);
ob_end_clean();

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$compileTest = new compileModelTest();

// 5. 执行测试步骤（至少5个）
r(is_string($compileTest->getLogsTest((object)array('engine' => 'jenkins', 'server' => 1, 'pipeline' => '{"name": "test"}'), (object)array('id' => 1, 'queue' => 123)))) && p() && e('1');
r(is_string($compileTest->getLogsTest((object)array('engine' => 'gitlab', 'server' => 2, 'pipeline' => '{"project": 123}'), (object)array('id' => 2, 'queue' => 456)))) && p() && e('1');
r($compileTest->getLogsTest((object)array('engine' => 'jenkins', 'server' => 1, 'pipeline' => '{"name": "test"}'), (object)array('id' => 3, 'queue' => 0)) === '' ? 1 : 0) && p() && e('1');
r($compileTest->getLogsTest((object)array('engine' => 'jenkins', 'server' => 1, 'pipeline' => ''), (object)array('id' => 4, 'queue' => 789)) === '' ? 1 : 0) && p() && e('1');
r($compileTest->getLogsTest((object)array('engine' => 'unknown', 'server' => 1, 'pipeline' => '{"name": "test"}'), (object)array('id' => 5, 'queue' => 999)) === '' ? 1 : 0) && p() && e('1');
