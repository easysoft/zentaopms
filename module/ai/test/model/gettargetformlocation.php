#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getTargetFormLocation();
timeout=0
cid=0

- 执行aiTest模块的getTargetFormLocationTest方法，参数是1,   @*story-change*
- 执行aiTest模块的getTargetFormLocationTest方法，参数是999,  
 -  @false
 - 属性1 @true
- 执行aiTest模块的getTargetFormLocationTest方法，参数是0,  
 -  @false
 - 属性1 @true
- 执行aiTest模块的getTargetFormLocationTest方法，参数是-1,  
 -  @false
 - 属性1 @true
- 执行aiTest模块的getTargetFormLocationTest方法，参数是5,  
 -  @false
 - 属性1 @true

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

$table = zenData('ai_prompt');
$table->id->range('1-5');
$table->name->range('需求变更,任务编辑,Bug修改,文档编辑,测试提示');
$table->desc->range('测试需求变更描述,测试任务编辑描述,测试Bug修改描述,测试文档编辑描述,测试用例描述');
$table->model->range('1,2,1,1,2');
$table->module->range('story,task,bug,doc,testcase');
$table->source->range(',story.title,story.spec,,task.name,task.desc,,bug.title,bug.steps,,doc.content,,case.title,');
$table->targetForm->range('story.change,task.edit,bug.edit,doc.edit,');
$table->purpose->range('润色需求,编辑任务,修改Bug,编辑文档,创建用例');
$table->elaboration->range('详细润色需求内容,详细编辑任务说明,详细修改Bug描述,详细编辑文档内容,详细创建用例说明');
$table->role->range('产品经理,项目经理,测试工程师,技术文档编写者,质量保证');
$table->characterization->range('专业需求分析,高效任务管理,细致Bug跟踪,清晰文档编写,全面测试覆盖');
$table->createdBy->range('admin');
$table->createdDate->range('`2023-08-10 10:00:00`,`2023-08-11 11:00:00`,`2023-08-12 12:00:00`,`2023-08-13 13:00:00`,`2023-08-14 14:00:00`');
$table->status->range('active');
$table->deleted->range('0');
$table->gen(5);

su('admin');

$aiTest = new aiTest();

r($aiTest->getTargetFormLocationTest(1, (object)array('story' => (object)array('id' => 1, 'status' => 'active', 'type' => 'story')))) && p('0') && e('*story-change*');
r($aiTest->getTargetFormLocationTest(999, (object)array())) && p('0,1') && e('false,true');
r($aiTest->getTargetFormLocationTest(0, (object)array())) && p('0,1') && e('false,true');
r($aiTest->getTargetFormLocationTest(-1, (object)array())) && p('0,1') && e('false,true');
r($aiTest->getTargetFormLocationTest(5, (object)array())) && p('0,1') && e('false,true');