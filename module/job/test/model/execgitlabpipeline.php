#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=jobModel->execGitlabPipeline();
timeout=0
cid=16839

- 测试执行jenkins job的情况属性status @create_fail
- 测试执行gitlab job的情况属性status @created
- 测试执行gitlab job的情况属性status @create_fail
- 测试执行gitlab job的情况属性status @~~
- 测试执行gitlab job的情况属性status @create_fail

*/

zenData('pipeline')->loadYaml('pipeline')->gen(5);
zenData('job')->loadYaml('job')->gen(5);
zenData('repo')->gen(5);
zenData('compile')->gen(0);

$job = new jobModelTest();
global $app;
$app->rawModule = 'job';
$app->rawMethod = 'exec';
r($job->execGitlabPipelineTest(1)) && p('status')&& e('create_fail'); // 测试执行jenkins job的情况
r($job->execGitlabPipelineTest(2)) && p('status')&& e('created');     // 测试执行gitlab job的情况
r($job->execGitlabPipelineTest(3)) && p('status')&& e('create_fail'); // 测试执行gitlab job的情况
r($job->execGitlabPipelineTest(4)) && p('status')&& e('~~'); // 测试执行gitlab job的情况
r($job->execGitlabPipelineTest(5)) && p('status')&& e('create_fail'); // 测试执行gitlab job的情况
