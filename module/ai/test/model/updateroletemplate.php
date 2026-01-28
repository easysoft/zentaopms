#!/usr/bin/env php
<?php

/**

title=测试 aiModel::updateRoleTemplate();
timeout=0
cid=15079

- 执行aiTest模块的updateRoleTemplateTest方法，参数是1, '请你扮演一名高级产品经理。', '负责产品战略规划、需求分析、项目管理等工作'  @1
- 执行aiTest模块的updateRoleTemplateTest方法，参数是999, '测试角色', '测试角色描述'  @1
- 执行aiTest模块的updateRoleTemplateTest方法，参数是2, '', ''  @1
- 执行aiTest模块的updateRoleTemplateTest方法，参数是3, null, null  @1
- 执行aiTest模块的updateRoleTemplateTest方法，参数是4, '你是一名专业的<script>alert  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('ai_agentrole');
$table->id->range('1-10');
$table->name->range('产品经理,开发工程师,测试工程师,QA工程师,文案编辑,项目经理');
$table->desc->range('负责产品管理,负责开发工作,负责测试工作,负责质量管理,负责文案编辑,负责项目管理');
$table->role->range('请你扮演一名资深的产品经理。,你是一名经验丰富的开发工程师。,作为一名资深的测试工程师。,假如你是一名资深的QA工程师。,你是一名文章写得很好的文案编辑。,请你扮演一名经验丰富的项目经理。');
$table->characterization->range('负责产品战略、设计、开发,精通多种编程语言和框架,测试工程师应该是专业且严谨的,熟悉质量管理体系和流程,文笔流畅、条理清晰,具备项目计划制定、进度管理');
$table->deleted->range('0');
$table->gen(5);

su('admin');

$aiTest = new aiModelTest();

r($aiTest->updateRoleTemplateTest(1, '请你扮演一名高级产品经理。', '负责产品战略规划、需求分析、项目管理等工作')) && p() && e('1');
r($aiTest->updateRoleTemplateTest(999, '测试角色', '测试角色描述')) && p() && e('1');
r($aiTest->updateRoleTemplateTest(2, '', '')) && p() && e('1');
r($aiTest->updateRoleTemplateTest(3, null, null)) && p() && e('1');
r($aiTest->updateRoleTemplateTest(4, '你是一名专业的<script>alert("test")</script>开发者', '擅长处理各种&特殊字符#和HTML标签的开发工作')) && p() && e('1');