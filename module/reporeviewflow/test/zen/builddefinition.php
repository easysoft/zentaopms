#!/usr/bin/env php
<?php
/**

title=测试 repoZen::buildCreateForm();
timeout=0
cid=0

- 测试审批流程ai评审分数第approvals条的score属性 @2
- 测试审批流程ai第ai条的enable属性 @1
- 测试审批流程最小审批人数第approvals条的minReviewers属性 @2
- 测试审批流程问题处理方式第issues条的addressOption属性 @testOption
- 测试审批流程自动归档第merge条的autoArchive属性 @1
*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('user')->gen(5);

su('admin');

$flowTest = new reporeviewflowZenTest();

$definition = new stdClass();
$definition->aiReviewScores = 2;
r($flowTest->buildDefinitionTest($definition)->ai) && p('approvals:score') && e('2'); // 测试审批流程ai评审分数

$definition->aiReview = 'enable';
r($flowTest->buildDefinitionTest($definition)) && p('ai:enable') && e('1'); // 测试审批流程ai

$definition->minReviewers = 2;
r($flowTest->buildDefinitionTest($definition)->reviewFlow) && p('approvals:minReviewers') && e('2'); // 测试审批流程最小审批人数

$definition->addressOption = 'testOption';
r($flowTest->buildDefinitionTest($definition)->reviewFlow) && p('issues:addressOption') && e('testOption'); // 测试审批流程问题处理方式

$definition->autoArchive = 'enable';
r($flowTest->buildDefinitionTest($definition)->reviewFlow) && p('merge:autoArchive') && e('1'); // 测试审批流程自动归档
