#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getObjectForPromptById();
timeout=0
cid=0

- 步骤1：story模块正常情况，返回数组包含两个元素 @2
- 步骤2：task模块正常情况，返回数组包含两个元素 @2
- 步骤3：不存在的prompt ID @0
- 步骤4：不存在的object ID @0
- 步骤5：deleted状态的prompt @0
- 步骤6：product模块测试，返回数组包含两个元素 @2
- 步骤7：bug模块但object不存在 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

// 2. zendata数据准备
$table = zenData('ai_prompt');
$table->id->range('1-10');
$table->name->range('story_prompt,task_prompt,bug_prompt,product_prompt,project_prompt,execution_prompt,test_prompt,dev_prompt,pm_prompt,qa_prompt');
$table->desc->range('测试描述内容{10}');
$table->model->range('0,1,2,3,4,5');
$table->module->range('story{2},task{2},bug{2},product{2},project{1},execution{1}');
$table->source->range('story.title,story.spec,task.name,task.desc,bug.title,bug.steps,product.name,product.desc,project.name,execution.name');
$table->targetForm->range('story.change,task.edit,bug.edit,product.edit,project.edit');
$table->purpose->range('测试目的内容{10}');
$table->elaboration->range('测试详细说明内容{10}');
$table->role->range('测试角色描述{10}');
$table->characterization->range('测试角色特征{10}');
$table->createdBy->range('admin,system,user1,user2');
$table->createdDate->range('`2023-08-10 10:00:00`,`2023-08-11 11:00:00`,`2023-08-12 12:00:00`');
$table->status->range('active{9},draft{1}');
$table->deleted->range('0{9},1{1}');
$table->gen(10);

$storyTable = zenData('story');
$storyTable->id->range('1-10');
$storyTable->title->range('用户登录功能,数据导出功能,权限管理功能,报表统计功能,文件上传功能,数据同步功能,权限控制功能,系统监控功能,日志管理功能,配置管理功能');
$storyTable->status->range('active{8},draft{2}');
$storyTable->version->range('1');
$storyTable->gen(10);

$storySpecTable = zenData('storyspec');
$storySpecTable->story->range('1-10');
$storySpecTable->version->range('1');
$storySpecTable->title->range('用户登录功能,数据导出功能,权限管理功能,报表统计功能,文件上传功能,数据同步功能,权限控制功能,系统监控功能,日志管理功能,配置管理功能');
$storySpecTable->spec->range('功能规格说明{10}');
$storySpecTable->verify->range('验收标准说明{10}');
$storySpecTable->gen(10);

$storyStageTable = zenData('storystage');
$storyStageTable->story->range('1-10');
$storyStageTable->branch->range('0');
$storyStageTable->stage->range('wait{5},active{3},developed{2}');
$storyStageTable->gen(10);

$taskTable = zenData('task');
$taskTable->id->range('1-10');
$taskTable->name->range('登录接口开发,数据库设计,前端页面开发,单元测试编写,集成测试,性能测试,代码审查,文档编写,部署脚本,监控配置');
$taskTable->status->range('wait{5},doing{3},done{2}');
$taskTable->gen(10);

$productTable = zenData('product');
$productTable->id->range('1-10');
$productTable->name->range('产品A,产品B,产品C,产品D,产品E,产品F,产品G,产品H,产品I,产品J');
$productTable->status->range('normal{8},closed{2}');
$productTable->gen(10);

$bugTable = zenData('bug');
$bugTable->id->range('1-10');
$bugTable->title->range('登录失败问题,数据同步错误,权限异常问题,页面显示错误,功能缺失问题,性能慢问题,兼容性问题,数据错误问题,界面错误问题,逻辑错误问题');
$bugTable->steps->range('重现步骤1,重现步骤2,重现步骤3,重现步骤4,重现步骤5,重现步骤6,重现步骤7,重现步骤8,重现步骤9,重现步骤10');
$bugTable->status->range('active{8},resolved{2}');
$bugTable->deleted->range('0');
$bugTable->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$aiTest = new aiTest();

// 5. 测试步骤（必须包含至少5个测试步骤）
r($aiTest->getObjectForPromptByIdTest(1, 1)) && p() && e('2'); // 步骤1：story模块正常情况，返回数组包含两个元素
r($aiTest->getObjectForPromptByIdTest(3, 2)) && p() && e('2'); // 步骤2：task模块正常情况，返回数组包含两个元素
r($aiTest->getObjectForPromptByIdTest(99, 1)) && p() && e('0'); // 步骤3：不存在的prompt ID
r($aiTest->getObjectForPromptByIdTest(1, 999)) && p() && e('0'); // 步骤4：不存在的object ID
r($aiTest->getObjectForPromptByIdTest(10, 1)) && p() && e('0'); // 步骤5：deleted状态的prompt
r($aiTest->getObjectForPromptByIdTest(7, 1)) && p() && e('2'); // 步骤6：product模块测试，返回数组包含两个元素
r($aiTest->getObjectForPromptByIdTest(5, 999)) && p() && e('0'); // 步骤7：bug模块但object不存在