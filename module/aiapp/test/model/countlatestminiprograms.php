#!/usr/bin/env php
<?php

/**

title=测试 aiappModel::countLatestMiniPrograms();
timeout=0
cid=15082

- 执行aiappTest模块的countLatestMiniProgramsTest方法  @7
- 执行aiappTest模块的countLatestMiniProgramsTest方法  @7
- 执行aiappTest模块的countLatestMiniProgramsTest方法  @7
- 执行aiappTest模块的countLatestMiniProgramsTest方法  @7
- 执行aiappTest模块的countLatestMiniProgramsTest方法  @7

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/aiapp.unittest.class.php';

$table = zenData('ai_miniprogram');
$table->id->range('1-10');
$table->name->range('小程序1,小程序2,小程序3,小程序4,小程序5,小程序6,小程序7,小程序8,小程序9,小程序10');
$table->category->range('work,personal,study');
$table->desc->range('测试小程序描述{10}');
$table->model->range('1,2,3');
$table->icon->range('writinghand-7');
$table->createdBy->range('admin,user');
$table->createdDate->range('`(-15d)`:`(-1d)`:1d,`(-40d)`:`(-32d)`:1d')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$table->editedBy->range('admin,user');
$table->editedDate->range('`(-15d)`:`(-1d)`:1d,`(-40d)`:`(-32d)`:1d')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$table->published->range('1{7},0{3}');
$table->publishedDate->range('`(-15d)`:`(-1d)`:1d,`(-40d)`:`(-32d)`:1d')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$table->deleted->range('0{8},1{2}');
$table->prompt->range('测试提示词{10}');
$table->builtIn->range('0,1');
$table->gen(10);

su('admin');

$aiappTest = new aiappTest();

r($aiappTest->countLatestMiniProgramsTest()) && p() && e('7');
r($aiappTest->countLatestMiniProgramsTest()) && p() && e('7');
r($aiappTest->countLatestMiniProgramsTest()) && p() && e('7');
r($aiappTest->countLatestMiniProgramsTest()) && p() && e('7');
r($aiappTest->countLatestMiniProgramsTest()) && p() && e('7');