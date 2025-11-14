#!/usr/bin/env php
<?php

/**

title=测试 testtaskZen::checkTaskForEdit();
timeout=0
cid=19233

- 执行testtaskTest模块的checkTaskForEditTest方法，参数是$validTask  @1
- 执行testtaskTest模块的checkTaskForEditTest方法，参数是$taskMissingName 第name条的0属性 @『测试单名称』不能为空。
- 执行testtaskTest模块的checkTaskForEditTest方法，参数是$taskMissingBuild 第build条的0属性 @『提测构建』不能为空。
- 执行testtaskTest模块的checkTaskForEditTest方法，参数是$taskMissingBegin 第begin条的0属性 @『开始日期』不能为空。
- 执行testtaskTest模块的checkTaskForEditTest方法，参数是$taskInvalidDateRange 第end条的0属性 @『结束日期』应当不小于『开始日期』。

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtaskzen.unittest.class.php';

su('admin');

$testtaskTest = new testtaskZenTest();

// 准备测试数据
$validTask = new stdclass();
$validTask->name = '有效测试单';
$validTask->build = '1';
$validTask->begin = '2024-01-01';
$validTask->end = '2024-01-31';
$validTask->desc = '测试描述';

$taskMissingName = new stdclass();
$taskMissingName->name = '';
$taskMissingName->build = '1';
$taskMissingName->begin = '2024-01-01';
$taskMissingName->end = '2024-01-31';

$taskMissingBuild = new stdclass();
$taskMissingBuild->name = '测试单名称';
$taskMissingBuild->build = '';
$taskMissingBuild->begin = '2024-01-01';
$taskMissingBuild->end = '2024-01-31';

$taskMissingBegin = new stdclass();
$taskMissingBegin->name = '测试单名称';
$taskMissingBegin->build = '1';
$taskMissingBegin->begin = '';
$taskMissingBegin->end = '2024-01-31';

$taskInvalidDateRange = new stdclass();
$taskInvalidDateRange->name = '测试单名称';
$taskInvalidDateRange->build = '1';
$taskInvalidDateRange->begin = '2024-01-31';
$taskInvalidDateRange->end = '2024-01-01';

r($testtaskTest->checkTaskForEditTest($validTask)) && p() && e('1');
r($testtaskTest->checkTaskForEditTest($taskMissingName)) && p('name:0') && e('『测试单名称』不能为空。');
r($testtaskTest->checkTaskForEditTest($taskMissingBuild)) && p('build:0') && e('『提测构建』不能为空。');
r($testtaskTest->checkTaskForEditTest($taskMissingBegin)) && p('begin:0') && e('『开始日期』不能为空。');
r($testtaskTest->checkTaskForEditTest($taskInvalidDateRange)) && p('end:0') && e('『结束日期』应当不小于『开始日期』。');