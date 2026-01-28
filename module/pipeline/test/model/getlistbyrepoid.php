#!/usr/bin/env php
<?php

/**

title=测试 jobModel::getListByRepoID();
timeout=0
cid=16845

- 测试步骤1：repo=1有3个未删除作业 @3
- 测试步骤2：repo=2有2个作业 @2
- 测试步骤3：不存在的repo返回空 @0
- 测试步骤4：repo=0的边界值测试 @1
- 测试步骤5：验证排序后第一个作业名称第8条的name属性 @Jenkins Job3
- 测试步骤6：验证返回字段完整性
 - 第8条的id属性 @8
 - 第8条的8:name属性 @Jenkins Job3
 - 第8条的8:lastStatus属性 @running
- 测试步骤7：验证特定repo的作业ID第6条的id属性 @6

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备测试数据
$jobTable = zenData('job');
$jobTable->id->range('1-10');
$jobTable->name->range('Jenkins Job1, Jenkins Job2, GitLab Pipeline1, Test Job1, API Test Job, Performance Test, Security Test, Jenkins Job3, Deploy Job, Monitor Job');
$jobTable->repo->range('1, 1, 2, 2, 3, 999, 0, 1, 888, 1');
$jobTable->product->range('1-3');
$jobTable->frame->range('phpunit, pytest, jest, junit, mocha, cypress, selenium, testng, robot, cucumber');
$jobTable->engine->range('jenkins{6}, gitlab{2}, github{2}');
$jobTable->server->range('1-5');
$jobTable->pipeline->range('/job/pipeline1, /job/pipeline2, main, develop, feature, master, test, release, deploy, monitor');
$jobTable->lastStatus->range('success, failed, running, success, \'\', failed, success, running, success, failed');
$jobTable->deleted->range('0, 0, 0, 0, 0, 0, 0, 0, 1, 1');
$jobTable->gen(10);

// 使用管理员身份登录
su('admin');

// 创建测试实例
$jobTest = new jobModelTest();

// 测试步骤1：有效版本库ID查询 - 检查repo=1的作业数量（排除已删除）
r(count($jobTest->getListByRepoIDTest(1))) && p() && e('3'); // 测试步骤1：repo=1有3个未删除作业
r(count($jobTest->getListByRepoIDTest(2))) && p() && e('2'); // 测试步骤2：repo=2有2个作业
r(count($jobTest->getListByRepoIDTest(99999))) && p() && e('0'); // 测试步骤3：不存在的repo返回空
r(count($jobTest->getListByRepoIDTest(0))) && p() && e('1'); // 测试步骤4：repo=0的边界值测试
r($jobTest->getListByRepoIDTest(1)) && p('8:name') && e('Jenkins Job3'); // 测试步骤5：验证排序后第一个作业名称
r($jobTest->getListByRepoIDTest(1)) && p('8:id,8:name,8:lastStatus') && e('8,Jenkins Job3,running'); // 测试步骤6：验证返回字段完整性
r($jobTest->getListByRepoIDTest(999)) && p('6:id') && e('6'); // 测试步骤7：验证特定repo的作业ID