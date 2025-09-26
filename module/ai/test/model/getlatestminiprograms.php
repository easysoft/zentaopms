#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getLatestMiniPrograms();
timeout=0
cid=0

- 步骤1：正常情况，返回符合条件的记录 @15
- 步骤2：不同排序方式，按名称升序 @15
- 步骤3：按ID倒序查询 @15
- 步骤4：验证返回的都是已发布的小程序 @1
- 步骤5：验证返回的都是未删除的小程序 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('ai_miniprogram');
$table->id->range('1-15');
$table->name->range('Career Guide,Writing Helper,Code Generator,Translator,Study Plan,Project Manager,Task Planner,Report Writer,Data Analyzer,Meeting Notes,Email Helper,Document Summary,Time Tracker,Goal Setter,Knowledge Base');
$table->category->range('personal,work,development');
$table->desc->range('AI miniprogram description,Helps improve work efficiency,Provides intelligent services');
$table->model->range('1-3');
$table->icon->range('writinghand-7,technologist-6,coding-1,translate-2,book-3');
$table->createdBy->range('admin,user,system');
$table->createdDate->range('`2025-08-01 10:00:00`,`2025-08-15 10:00:00`');
$table->editedBy->range('admin,user,system');
$table->editedDate->range('`2025-08-20 10:00:00`,`2025-09-06 10:00:00`');
$table->published->range('1{15}'); // 全部设置为已发布
$table->publishedDate->range('`2025-09-01 10:00:00`,`2025-09-25 10:00:00`'); // 设置在最近一个月内的发布时间
$table->deleted->range('0{15}'); // 全部设置为未删除
$table->prompt->range('Please help me generate,This is used for,AI assistant will');
$table->builtIn->range('0,1');
$table->gen(15);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$aiTest = new aiTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$results = $aiTest->getLatestMiniProgramsTest();
r(count($results)) && p() && e('15'); // 步骤1：正常情况，返回符合条件的记录
r(count($aiTest->getLatestMiniProgramsTest(null, 'name_asc'))) && p() && e('15'); // 步骤2：不同排序方式，按名称升序
r(count($aiTest->getLatestMiniProgramsTest(null, 'id_desc'))) && p() && e('15'); // 步骤3：按ID倒序查询
r(!empty($results) ? $results[array_keys($results)[0]]->published : '0') && p() && e('1'); // 步骤4：验证返回的都是已发布的小程序
r(!empty($results) ? $results[array_keys($results)[0]]->deleted : '1') && p() && e('0'); // 步骤5：验证返回的都是未删除的小程序