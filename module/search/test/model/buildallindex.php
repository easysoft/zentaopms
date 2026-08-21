#!/usr/bin/env php
<?php

/**

title=测试 searchModel::buildAllIndex();
timeout=0
cid=18292

- 测试从空类型开始构建时返回首个有数据的对象类型属性type @build
- 测试从空类型开始构建时返回正确的数量属性count @2
- 测试按 lastID 分页构建 build 索引时返回正确的数量属性count @1
- 测试按 lastID 分页构建 build 索引时返回正确的最后 ID属性lastID @2
- 测试从 task 类型开始构建时返回 task 类型属性type @task
- 测试从 task 类型开始构建时返回正确的数量属性count @3
- 测试从 aiapp 类型开始且后续无数据时返回完成状态属性finished @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('searchindex')->gen(0);
zenData('searchdict')->gen(0);

zenData('bug')->gen(0);
zenData('build')->gen(0);
$build = zenData('build');
$build->id->range('1-2');
$build->name->range('Build 1,Build 2');
$build->desc->range('Build desc 1,Build desc 2');
$build->filePath->range('/tmp/build1,/tmp/build2');
$build->scmPath->range('/repo/build1,/repo/build2');
$build->date->range('`2026-06-30`{2}');
$build->gen(2);

zenData('task')->gen(0);
$task = zenData('task');
$task->id->range('1-3');
$task->name->range('Task 1,Task 2,Task 3');
$task->desc->range('Task desc 1,Task desc 2,Task desc 3');
$task->openedDate->range('`2026-06-30`{3}');
$task->deleted->range('0');
$task->gen(3);

su('admin');

$search = new searchModelTest();

$buildFromStart = $search->buildAllIndexTest();
$buildNextPage  = $search->buildAllIndexTest('build', 1);
$taskBatch      = $search->buildAllIndexTest('task');
if(isset($config->search->fields->aiapp)) $tester->dao->delete()->from(TABLE_AI_MINIPROGRAM)->exec();
global $config;
$config->search->fields = (object)array('aiapp' => isset($config->search->fields->aiapp) ? $config->search->fields->aiapp : (object)array());
$finishedResult = $search->buildAllIndexTest('aiapp');

r($buildFromStart) && p('type') && e('build');      // 测试从空类型开始构建时返回首个有数据的对象类型
r($buildFromStart) && p('count') && e('2');         // 测试从空类型开始构建时返回正确的数量
r($buildNextPage)  && p('count') && e('1');         // 测试按 lastID 分页构建 build 索引时返回正确的数量
r($buildNextPage)  && p('lastID') && e('2');        // 测试按 lastID 分页构建 build 索引时返回正确的最后 ID
r($taskBatch)      && p('type') && e('task');       // 测试从 task 类型开始构建时返回 task 类型
r($taskBatch)      && p('count') && e('3');         // 测试从 task 类型开始构建时返回正确的数量
r($finishedResult) && p('finished') && e('1');      // 测试从 aiapp 类型开始且后续无数据时返回完成状态