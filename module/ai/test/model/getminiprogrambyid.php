#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getMiniProgramByID();
timeout=0
cid=15038

- 执行aiTest模块的getMiniProgramByIDTest方法，参数是1 属性id @1
- 执行aiTest模块的getMiniProgramByIDTest方法，参数是2 属性name @Work Report
- 执行aiTest模块的getMiniProgramByIDTest方法，参数是999  @0
- 执行aiTest模块的getMiniProgramByIDTest方法  @0
- 执行aiTest模块的getMiniProgramByIDTest方法，参数是'abc'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

$table = zenData('ai_miniprogram');
$table->id->range('1-10');
$table->name->range('Career Guide,Work Report,Market Analysis,Fitness Plan,Creative Master{2},Test App{3}');
$table->category->range('personal,work,life,creative');
$table->desc->range('Test description{10}');
$table->model->range('1-3');
$table->icon->range('writinghand-7,technologist-6,chart-6');
$table->createdBy->range('admin{5},user{5}');
$table->createdDate->range('`2024-01-01 00:00:00`');
$table->editedBy->range('admin{5},user{5}');
$table->editedDate->range('`2024-01-01 00:00:00`');
$table->published->range('0{3},1{7}');
$table->publishedDate->range('`2024-01-01 00:00:00`');
$table->deleted->range('0{8},1{2}');
$table->prompt->range('Test prompt{10}');
$table->builtIn->range('0{7},1{3}');
$table->gen(10);

su('admin');

$aiTest = new aiTest();

r($aiTest->getMiniProgramByIDTest(1)) && p('id') && e('1');
r($aiTest->getMiniProgramByIDTest(2)) && p('name') && e('Work Report');
r($aiTest->getMiniProgramByIDTest(999)) && p() && e('0');
r($aiTest->getMiniProgramByIDTest(0)) && p() && e('0');
r($aiTest->getMiniProgramByIDTest('abc')) && p() && e('0');