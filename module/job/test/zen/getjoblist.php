#!/usr/bin/env php
<?php

/**

title=测试 jobZen::getJobList();
timeout=0
cid=0

- 步骤1：正常情况，测试repoID=1第1条的name属性 @这是一个Job1
- 步骤2：边界值，repoID=0时返回所有数据 @10
- 步骤3：engine字段处理验证第10条的engine属性 @GitLab
- 步骤4：productName字段设置验证第2条的productName属性 @正常产品2
- 步骤5：triggerType字段处理验证第1条的triggerType属性 @目录改动(/module/caselib)

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/job.unittest.class.php';

// 2. zendata数据准备
$job = zenData('job');
$job->loadYaml('job_getjoblist', false, 2)->gen(10);

$repo = zenData('repo');
$repo->loadYaml('repo_getjoblist', false, 2)->gen(5);

$pipeline = zenData('pipeline');
$pipeline->loadYaml('pipeline_getjoblist', false, 2)->gen(5);

$product = zenData('product');
$product->loadYaml('product_getjoblist', false, 2)->gen(3);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$jobTest = new jobTest();

// 5. 测试步骤（必须包含至少5个测试步骤）
r($jobTest->getJobListTest(1)) && p('1:name') && e('这是一个Job1'); // 步骤1：正常情况，测试repoID=1
r(count($jobTest->getJobListTest(0))) && p() && e(10); // 步骤2：边界值，repoID=0时返回所有数据  
r($jobTest->getJobListTest(0, '', 'id_desc')) && p('10:engine') && e('GitLab'); // 步骤3：engine字段处理验证
r($jobTest->getJobListTest(2)) && p('2:productName') && e('正常产品2'); // 步骤4：productName字段设置验证
r($jobTest->getJobListTest(1)) && p('1:triggerType') && e('目录改动(/module/caselib)'); // 步骤5：triggerType字段处理验证