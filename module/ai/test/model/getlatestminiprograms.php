#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getLatestMiniPrograms();
timeout=0
cid=0

- 步骤1：正常情况，返回10条符合条件的记录 @10
- 步骤2：不同排序方式，按名称升序 @10
- 步骤3：按ID倒序查询 @10
- 步骤4：验证返回的都是已发布的小程序第4条的published属性 @1
- 步骤5：验证返回的都是未删除的小程序第5条的deleted属性 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('ai_miniprogram');
$table->id->range('1-15');
$table->name->range('Career Guide,Writing Helper,Code Generator,Translator,Study Plan,Project Manager');
$table->category->range('personal,work,development');
$table->desc->range('AI miniprogram description,Helps improve work efficiency,Provides intelligent services');
$table->model->range('1-3');
$table->icon->range('writinghand-7,technologist-6,coding-1,translate-2,book-3');
$table->createdBy->range('admin,user,system');
$table->createdDate->range('`2025-08-01 10:00:00`,`2025-08-15 10:00:00`');
$table->editedBy->range('admin,user,system');
$table->editedDate->range('`2025-08-20 10:00:00`,`2025-09-06 10:00:00`');
$table->published->range('0{3},1{12}');
$table->publishedDate->range('`2025-08-10 10:00:00`,`2025-09-06 10:00:00`');
$table->deleted->range('0{13},1{2}');
$table->prompt->range('Please help me generate,This is used for,AI assistant will');
$table->builtIn->range('0,1');
$table->gen(15);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$aiTest = new aiTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($aiTest->getLatestMiniProgramsTest())) && p() && e('10'); // 步骤1：正常情况，返回10条符合条件的记录
r(count($aiTest->getLatestMiniProgramsTest(null, 'name_asc'))) && p() && e('10'); // 步骤2：不同排序方式，按名称升序
r(count($aiTest->getLatestMiniProgramsTest(null, 'id_desc'))) && p() && e('10'); // 步骤3：按ID倒序查询
r($aiTest->getLatestMiniProgramsTest()) && p('4:published') && e('1'); // 步骤4：验证返回的都是已发布的小程序
r($aiTest->getLatestMiniProgramsTest()) && p('5:deleted') && e('0'); // 步骤5：验证返回的都是未删除的小程序