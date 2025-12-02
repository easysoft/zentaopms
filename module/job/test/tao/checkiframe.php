#!/usr/bin/env php
<?php

/**

title=测试 jobTao::checkIframe();
timeout=0
cid=16855

- 执行jobTest模块的checkIframeTest方法，参数是$job1 第frame条的0属性 @SonarQube工具/框架仅在构建引擎为JenKins的情况下使用
- 执行jobTest模块的checkIframeTest方法，参数是$job2 第repo条的0属性 @此代码库已关联流水线『1-这是一个Job1』
- 执行jobTest模块的checkIframeTest方法，参数是$job3, 2  @1
- 执行jobTest模块的checkIframeTest方法，参数是$job4  @1
- 执行jobTest模块的checkIframeTest方法，参数是$job5  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/job.unittest.class.php';

zendata('job')->loadYaml('job_checkiframe', false, 2)->gen(10);

su('admin');

$jobTest = new jobTest();

// 测试步骤1：非jenkins引擎但frame为sonarqube的情况
$job1 = new stdClass();
$job1->engine = 'gitlab';
$job1->frame = 'sonarqube';
$job1->repo = 1;
$job1->projectKey = 'test-project';
$job1->sonarqubeServer = 1;

r($jobTest->checkIframeTest($job1)) && p('frame:0') && e('SonarQube工具/框架仅在构建引擎为JenKins的情况下使用');

// 测试步骤2：jenkins引擎且frame为sonarqube但repo已存在关联任务的情况
$job2 = new stdClass();
$job2->engine = 'jenkins';
$job2->frame = 'sonarqube';
$job2->repo = 1;
$job2->projectKey = 'new-project';
$job2->sonarqubeServer = 1;

r($jobTest->checkIframeTest($job2)) && p('repo:0') && e('此代码库已关联流水线『1-这是一个Job1』');

// 测试步骤3：jenkins引擎且frame为sonarqube但repo为空的情况
$job3 = new stdClass();
$job3->engine = 'jenkins';
$job3->frame = 'sonarqube';
$job3->repo = 0;
$job3->projectKey = 'unique-key-test';
$job3->sonarqubeServer = 1;

r($jobTest->checkIframeTest($job3, 2)) && p() && e('1');

// 测试步骤4：jenkins引擎且frame为sonarqube的正常情况
$job4 = new stdClass();
$job4->engine = 'jenkins';
$job4->frame = 'sonarqube';
$job4->repo = 6;
$job4->projectKey = 'unique-project';
$job4->sonarqubeServer = 1;

r($jobTest->checkIframeTest($job4)) && p() && e('1');

// 测试步骤5：非sonarqube frame的正常情况
$job5 = new stdClass();
$job5->engine = 'jenkins';
$job5->frame = 'phpunit';
$job5->repo = 5;
$job5->projectKey = '';
$job5->sonarqubeServer = 0;

r($jobTest->checkIframeTest($job5)) && p() && e('1');