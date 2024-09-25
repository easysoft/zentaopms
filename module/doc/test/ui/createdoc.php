#!/usr/bin/env php
<?php

/**

title=创建文档测试
timeout=0

- 创建我的空间下草稿文档，创建成功
 - 测试结果 @我的空间草稿文档创建成功
 - 最终测试状态 @SUCCESS
- 创建我的空间下文档，创建成功
 - 测试结果 @我的空间文档创建成功
 - 最终测试状态 @SUCCESS
- 创建产品空间下的文档，创建成功
 - 测试结果 @产品空间文档创建成功
 - 最终测试结果 @SUCCESS
- 创建项目空间下的文档，创建成功
 - 测试结果 @项目空间文档创建成功
 - 最终测试结果 @SUCCESS

 */
chdir(__DIR__);
include '../lib/createdoc.ui.class.php';

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

$plan = new stdClass();
$plan->begin = '2024-09-01';
$plan->end   = '2024-11-01';

r($tester->createDraft($draftName))                  && p('message,status') && e('创建草稿成功,SUCCESS');     //创建草稿文档成功
r($tester->createDoc($docName))                      && p('message,status') && e('创建文档成功,SUCCESS');     //创建文档成功
r($tester->createProductDoc($productName, $docName)) && p('message,status') && e('创建产品文档成功,SUCCESS'); //创建产品空间下的文档成功
r($tester->createProjectDoc($projectName, $executionName, $plan, $docName)) && p('message,status') && e('创建项目文档成功,SUCCESS'); //创建项目空间下的文档成功
