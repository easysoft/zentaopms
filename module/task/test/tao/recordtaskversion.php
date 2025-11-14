#!/usr/bin/env php
<?php

/**

title=测试 taskTao::recordTaskVersion();
timeout=0
cid=18888

- 执行taskTest模块的recordTaskVersionTest方法  @1
- 执行taskTest模块的recordTaskVersionTest方法  @1
- 执行taskTest模块的recordTaskVersionTest方法  @1
- 执行taskTest模块的recordTaskVersionTest方法  @1
- 执行taskTest模块的recordTaskVersionTest方法  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

$table = zenData('taskspec');
$table->task->range('1-10');
$table->version->range('1-3');
$table->name->range('任务A,任务B,任务C{3}');
$table->estStarted->range('[2023-01-01,2023-01-02,2023-01-03]{2}');
$table->deadline->range('[2023-02-01,2023-02-02,2023-02-03]{2}');
$table->gen(5);

su('admin');

$taskTest = new taskTest();

r($taskTest->recordTaskVersionTest((object)array('id' => 11, 'version' => 1, 'name' => '新任务', 'estStarted' => '2023-03-01', 'deadline' => '2023-03-15'))) && p() && e('1');
r($taskTest->recordTaskVersionTest((object)array('id' => 12, 'version' => 2, 'name' => '测试任务', 'estStarted' => null, 'deadline' => null))) && p() && e('1');
r($taskTest->recordTaskVersionTest((object)array('id' => 13, 'version' => 1, 'name' => '基础任务', 'estStarted' => '2023-04-01', 'deadline' => '2023-04-15'))) && p() && e('1');
r($taskTest->recordTaskVersionTest((object)array('id' => 14, 'version' => 1, 'name' => '特殊字符<>&"任务', 'estStarted' => '2023-05-01', 'deadline' => '2023-05-15'))) && p() && e('1');
r($taskTest->recordTaskVersionTest((object)array('id' => 1, 'version' => 1, 'name' => '重复任务', 'estStarted' => '2023-06-01', 'deadline' => '2023-06-15'))) && p() && e('0');