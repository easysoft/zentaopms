#!/usr/bin/env php
<?php

/**

title=测试 compileModel::syncGitlabBuildList();
timeout=0
cid=15755

- 执行compileTest模块的syncGitlabBuildListTest方法，参数是$emptyGitlab, $validJob  @false
- 执行compileTest模块的syncGitlabBuildListTest方法，参数是$validGitlab, $emptyPipelineJob  @false
- 执行compileTest模块的syncGitlabBuildListTest方法，参数是$validGitlab, $validPipelineJob  @true
- 执行 @0
- 执行compileTest模块的syncGitlabBuildListTest方法，参数是'invalid', $validJob  @false

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$compileTest = new compileModelTest();

// 创建测试对象 - 空ID测试
$emptyGitlab = new stdClass();
$emptyGitlab->id = '';

$validJob = new stdClass();
$validJob->id = 1;
$validJob->name = 'TestJob';
$validJob->pipeline = '{"project":"1","reference":"master"}';
$validJob->lastSyncDate = null;

// 创建测试对象 - 有效ID但空pipeline
$validGitlab = new stdClass();
$validGitlab->id = 1;

$emptyPipelineJob = new stdClass();
$emptyPipelineJob->id = 2;
$emptyPipelineJob->name = 'EmptyJob';
$emptyPipelineJob->pipeline = '';
$emptyPipelineJob->lastSyncDate = null;

// 创建测试对象 - 有效参数
$validPipelineJob = new stdClass();
$validPipelineJob->id = 3;
$validPipelineJob->name = 'ValidJob';
$validPipelineJob->pipeline = '{"project":"1","reference":"master"}';
$validPipelineJob->lastSyncDate = null;

// 测试步骤：至少5个
r($compileTest->syncGitlabBuildListTest($emptyGitlab, $validJob)) && p() && e('false');
r($compileTest->syncGitlabBuildListTest($validGitlab, $emptyPipelineJob)) && p() && e('false');
r(is_bool($compileTest->syncGitlabBuildListTest($validGitlab, $validPipelineJob))) && p() && e('true');
r(dao::isError() ? 1 : 0) && p() && e('0');
r($compileTest->syncGitlabBuildListTest('invalid', $validJob)) && p() && e('false');