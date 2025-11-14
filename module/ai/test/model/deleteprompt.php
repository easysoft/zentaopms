#!/usr/bin/env php
<?php

/**

title=测试 aiModel::deletePrompt();
timeout=0
cid=15019

- 执行aiTest模块的deletePromptTest方法，参数是1  @1
- 执行aiTest模块的deletePromptTest方法，参数是999  @0
- 执行aiTest模块的deletePromptTest方法  @0
- 执行aiTest模块的deletePromptTest方法，参数是-1  @0
- 执行aiTest模块的deletePromptTest方法，参数是'abc'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

$table = zenData('ai_prompt');
$table->id->range('1-10');
$table->name->range('需求润色,一键拆用例,任务润色,需求转任务,Bug润色,代码审查,测试用例生成,API文档生成,项目总结,技术调研');
$table->desc->range('优化需求描述,生成测试用例,优化任务描述,转化需求为任务,优化Bug描述,代码质量审查,自动生成测试,API文档自动化,项目总结报告,技术调研分析');
$table->model->range('1-3');
$table->module->range('story,testcase,task,story,bug,code,test,api,project,research');
$table->status->range('active{8},draft{2}');
$table->createdBy->range('admin');
$table->createdDate->range('`2023-01-01 00:00:00`');
$table->deleted->range('0');
$table->gen(10);

su('admin');

$aiTest = new aiTest();

r($aiTest->deletePromptTest(1)) && p() && e('1');
r($aiTest->deletePromptTest(999)) && p() && e('0');
r($aiTest->deletePromptTest(0)) && p() && e('0');
r($aiTest->deletePromptTest(-1)) && p() && e('0');
r($aiTest->deletePromptTest('abc')) && p() && e('0');