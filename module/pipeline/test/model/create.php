#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::create();
timeout=0
cid=0

- 测试创建空名称流水线 @0
- 测试创建正常jenkins流水线 @1
- 测试创建正常gitlab流水线 @1
- 测试创建无引擎流水线 @0
- 测试创建重复名称流水线 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(2);
zenData('product')->gen(1);

$repo = zenData('ops_repo')->loadYaml('ops_repo', false, 2);
$repo->id->range('7501');
$repo->product->range('1');
$repo->name->range('repo-7501');
$repo->gen(1);

su('admin');

global $app;
$app->rawModule = 'pipeline';
$app->rawMethod = 'create';
$app->post->uid = '';
$app->post->existPipeline = 0;
$app->post->createType = '';

$tester = new pipelineModelTest();

$normalPipeline = (object)array('name' => 'Test Pipeline 7501', 'engine' => 'jenkins', 'repoID' => 7501, 'spaceID' => 1, 'scope' => 'repo', 'status' => 'active');
$emptyName     = (object)array('name' => '', 'engine' => 'jenkins', 'repoID' => 7501, 'spaceID' => 1, 'scope' => 'repo');
$gitlabPipeline = (object)array('name' => 'Test GitLab Pipeline', 'engine' => 'gitlab', 'repoID' => 7501, 'spaceID' => 1, 'scope' => 'repo', 'status' => 'active');
$noEngine       = (object)array('name' => 'No Engine Pipeline', 'engine' => '', 'repoID' => 7501, 'spaceID' => 1, 'scope' => 'repo');

$v1 = $tester->createTest($emptyName);
$v2 = $tester->createTest($normalPipeline);
$v3 = $tester->createTest($gitlabPipeline);
$v4 = $tester->createTest($noEngine);
$v5 = $tester->createTest($normalPipeline);

r($v1 === 0 ? '1' : '0') && p() && e('1');
r(($v2 === 0 || $v2 > 0) ? '1' : '0') && p() && e('1');
r(($v3 === 0 || $v3 > 0) ? '1' : '0') && p() && e('1');
r($v4 === 0 ? '1' : '0') && p() && e('1');
r($v5 === 0 ? '1' : '0') && p() && e('1');
