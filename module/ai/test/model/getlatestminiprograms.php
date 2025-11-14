#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getLatestMiniPrograms();
timeout=0
cid=15037

- 执行aiTest模块的getLatestMiniProgramsTest方法  @15
- 执行aiTest模块的getLatestMiniProgramsTest方法，参数是null, 'name_asc'  @15
- 执行aiTest模块的getLatestMiniProgramsTest方法，参数是null, 'id_desc'  @15
- 执行$results) ? reset($results)->published : '0 @1
- 执行$results) ? reset($results)->deleted : '1 @0
- 执行resultsWithOrder) && strtotime(reset($resultsWithOrder)模块的publishedDate) > strtotime方法，参数是'-1 months'  @1
- 执行aiTest模块的getLatestMiniProgramsTest方法，参数是null, ''  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

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
$table->published->range('1{15}');
$table->publishedDate->range('`2025-09-01 10:00:00`,`2025-09-25 10:00:00`');
$table->deleted->range('0{15}');
$table->prompt->range('Please help me generate,This is used for,AI assistant will');
$table->builtIn->range('0,1');
$table->gen(15);

su('admin');

$aiTest = new aiTest();

r(count($aiTest->getLatestMiniProgramsTest())) && p() && e('15');
r(count($aiTest->getLatestMiniProgramsTest(null, 'name_asc'))) && p() && e('15');
r(count($aiTest->getLatestMiniProgramsTest(null, 'id_desc'))) && p() && e('15');
$results = $aiTest->getLatestMiniProgramsTest();
r(!empty($results) ? reset($results)->published : '0') && p() && e('1');
r(!empty($results) ? reset($results)->deleted : '1') && p() && e('0');
$resultsWithOrder = $aiTest->getLatestMiniProgramsTest(null, 'publishedDate_desc');
r(!empty($resultsWithOrder) && strtotime(reset($resultsWithOrder)->publishedDate) > strtotime('-1 months')) && p() && e('1');
r(!is_null($aiTest->getLatestMiniProgramsTest(null, ''))) && p() && e('1');