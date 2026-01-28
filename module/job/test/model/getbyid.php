#!/usr/bin/env php
<?php

/**

title=测试 jobModel::getByID();
timeout=0
cid=16841

- 测试步骤1：查询有效jenkins类型job的基本信息
 - 属性id @1
 - 属性engine @jenkins
 - 属性name @Jenkins Job
 - 属性pipeline @test-pipeline
- 测试步骤2：查询有效gitlab类型job基本信息
 - 属性id @2
 - 属性engine @gitlab
 - 属性name @Gitlab Job
- 测试步骤3：查询不存在的job ID返回空对象属性id @~~
- 测试步骤4：测试边界值：ID为0的情况属性id @~~
- 测试步骤5：验证jenkins引擎正常pipeline
 - 属性id @6
 - 属性engine @jenkins
 - 属性pipeline @simple
- 测试步骤6：测试负数ID的边界情况属性id @~~
- 测试步骤7：验证jenkins引擎基本信息
 - 属性id @5
 - 属性engine @jenkins

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备测试数据
$table = zenData('job');
$table->id->range('1-7');
$table->name->range('Jenkins Job,Gitlab Job,Jenkins Normal,Jenkins Pipeline,Empty Job,Normal Job,Test Job');
$table->engine->range('jenkins,gitlab,jenkins,jenkins,jenkins,jenkins,jenkins');
$table->pipeline->range('/job/test-pipeline,{"project":123,"reference":"master"},normal-pipeline,/job/complex/pipeline/path,,simple,test');
$table->frame->range('phpunit,sonarqube,phpunit,sonarqube,phpunit,phpunit,phpunit');
$table->repo->range('1-7');
$table->deleted->range('0');
$table->gen(7);

// 用户登录
su('admin');

// 创建测试实例
$job = new jobModelTest();

r($job->getByIdTest(1)) && p('id,engine,name,pipeline') && e('1,jenkins,Jenkins Job,test-pipeline'); // 测试步骤1：查询有效jenkins类型job的基本信息
r($job->getByIdTest(2)) && p('id,engine,name') && e('2,gitlab,Gitlab Job');                    // 测试步骤2：查询有效gitlab类型job基本信息
r($job->getByIdTest(999)) && p('id') && e('~~');                                               // 测试步骤3：查询不存在的job ID返回空对象
r($job->getByIdTest(0)) && p('id') && e('~~');                                                 // 测试步骤4：测试边界值：ID为0的情况
r($job->getByIdTest(6)) && p('id,engine,pipeline') && e('6,jenkins,simple');              // 测试步骤5：验证jenkins引擎正常pipeline
r($job->getByIdTest(-1)) && p('id') && e('~~');                                                // 测试步骤6：测试负数ID的边界情况
r($job->getByIdTest(5)) && p('id,engine') && e('5,jenkins');                                   // 测试步骤7：验证jenkins引擎基本信息