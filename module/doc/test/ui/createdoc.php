#!/usr/bin/env php
<?php

/**

title=创建文档测试
timeout=0
cid=0

- 创建草稿文档成功
 - 测试结果 @创建草稿成功
 - 最终测试状态 @SUCCESS
- 创建文档成功
 - 测试结果 @创建文档成功
 - 最终测试状态 @SUCCESS
- 创建产品空间下的文档成功
 - 测试结果 @创建产品文档成功
 - 最终测试状态 @SUCCESS
- 创建项目空间下的文档成功
 - 测试结果 @创建项目文档成功
 - 最终测试状态 @SUCCESS
- 创建团队空间文档成功
 - 测试结果 @创建团队文档成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/createdoc.ui.class.php';

zendata('doclib')->loadYaml('custom', false, 2)->gen(1);
$tester = new createDocTester();
$tester->login();

$draftName = new stdClass();
$draftName->nullName = '';
$draftName->dftName  = '草稿文档1';

$docName = new stdClass();
$docName->dcName = '文档1';

$productName = new stdClass();
$productName->fstProduct = '产品1';
$productName->secProduct = '产品2';

$projectName = new stdClass();
$projectName->fstProject = '项目1';

$executionName = new stdClass();
$executionName->fstExecution = '执行1';

$teamSpace = new stdClass();
$teamSpace->spaceName = '团队空间1';

$teamLib = new stdClass();
$teamLib->libName = '团队文档库A';

$plan = new stdClass();
$plan->begin = '2024-09-01';
$plan->end   = '2024-11-01';

r($tester->createDraft($draftName))                  && p('message,status') && e('创建草稿成功,SUCCESS');     //创建草稿文档成功
r($tester->createDoc($docName))                      && p('message,status') && e('创建文档成功,SUCCESS');     //创建文档成功
r($tester->createProductDoc($productName, $docName)) && p('message,status') && e('创建产品文档成功,SUCCESS'); //创建产品空间下的文档成功
r($tester->createProjectDoc($projectName, $executionName, $plan, $docName)) && p('message,status') && e('创建项目文档成功,SUCCESS'); //创建项目空间下的文档成功
r($tester->createTeamDoc($teamSpace, $teamLib, $docName))                   && p('message,status') && e('创建团队文档成功,SUCCESS'); //创建团队空间文档成功
