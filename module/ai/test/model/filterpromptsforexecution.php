#!/usr/bin/env php
<?php

/**

title=测试 aiModel::filterPromptsForExecution();
timeout=0
cid=15024

- 执行aiTest模块的filterPromptsForExecutionTest方法，参数是array  @0
- 执行aiTest模块的filterPromptsForExecutionTest方法，参数是array  @1
- 执行aiTest模块的filterPromptsForExecutionTest方法，参数是array  @1
- 执行aiTest模块的filterPromptsForExecutionTest方法，参数是array  @1
- 执行aiTest模块的filterPromptsForExecutionTest方法，参数是array 第0条的name属性 @test5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('ai_prompt');
$table->id->range('1-10');
$table->name->range('prompt1,prompt2,prompt3{2},prompt4{3},prompt5{3}');
$table->module->range('story{3},task{3},bug{3},testcase');
$table->source->range('story.story{3},task.task{3},bug.bug{3},testcase.case');
$table->targetForm->range('story.create{3},task.edit{3},bug.edit{3},testcase.edit');
$table->purpose->range('创建需求,编辑任务,修复缺陷,测试用例{7}');
$table->status->range('active{8},draft{2}');
$table->createdBy->range('admin{5},user{5}');
$table->createdDate->range('`2024-01-01 00:00:00`');
$table->deleted->range('0{9},1');
$table->gen(10);

su('admin');

$aiTest = new aiModelTest();

r(count($aiTest->filterPromptsForExecutionTest(array()))) && p() && e('0');
r(count($aiTest->filterPromptsForExecutionTest(array((object)array('id' => 1, 'name' => 'test1', 'module' => 'story', 'source' => 'story.story', 'targetForm' => 'story.create', 'purpose' => '测试', 'status' => 'active'))))) && p() && e('1');
r(count($aiTest->filterPromptsForExecutionTest(array((object)array('id' => 2, 'name' => '', 'module' => 'story', 'source' => 'story.story', 'targetForm' => 'story.create', 'purpose' => '测试', 'status' => 'active'), (object)array('id' => 3, 'name' => 'test3', 'module' => 'story', 'source' => 'story.story', 'targetForm' => 'story.create', 'purpose' => '测试', 'status' => 'active'))))) && p() && e('1');
r(count($aiTest->filterPromptsForExecutionTest(array((object)array('id' => 4, 'name' => 'test4', 'module' => 'task', 'source' => 'task.task', 'targetForm' => 'task.edit', 'purpose' => '测试', 'status' => 'active')), false))) && p() && e('1');
r($aiTest->filterPromptsForExecutionTest(array((object)array('id' => 5, 'name' => 'test5', 'module' => 'story', 'source' => 'story.story', 'targetForm' => 'story.create', 'purpose' => '测试', 'status' => 'active')), true)) && p('0:name') && e('test5');