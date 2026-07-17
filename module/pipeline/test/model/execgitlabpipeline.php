#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::execGitlabPipeline();
timeout=0
cid=0

- 测试执行gitlab流水线(无API) @0
- 测试执行gitlab流水线(tag触发无API) @0
- 测试执行空引擎流水线 @0
- 测试执行jenkins流水线(manual触发) @0
- 测试执行不存在流水线 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(2);
su('admin');

$tester = new pipelineModelTest();

$gitlabPipeline = (object)array('id' => 1, 'engine' => 'gitlab', 'providerID' => 0, 'defaultBranch' => 'main', 'externalPipeline' => 'test', 'repoID' => 1);
$jenkinsPipeline = (object)array('id' => 2, 'engine' => 'jenkins', 'providerID' => 0, 'defaultBranch' => 'main', 'externalPipeline' => 'test', 'repoID' => 1);
$emptyPipeline = (object)array('id' => 3, 'engine' => '', 'providerID' => 0, 'defaultBranch' => 'main', 'externalPipeline' => 'test', 'repoID' => 1);

$v1 = $tester->execGitlabPipelineTest($gitlabPipeline, 'manual');
$v2 = $tester->execGitlabPipelineTest($gitlabPipeline, 'tag');
$v3 = $tester->execGitlabPipelineTest($emptyPipeline, 'manual');
$v4 = $tester->execGitlabPipelineTest($jenkinsPipeline, 'manual');
$v5 = $tester->execGitlabPipelineTest((object)array('id' => 9999, 'engine' => 'gitlab', 'providerID' => 0, 'defaultBranch' => 'main', 'externalPipeline' => 'test', 'repoID' => 1));

r($v1) && p() && e('0');
r($v2) && p() && e('0');
r($v3) && p() && e('0');
r($v4) && p() && e('0');
r($v5) && p() && e('0');
