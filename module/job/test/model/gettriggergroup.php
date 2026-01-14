#!/usr/bin/env php
<?php

/**

title=测试 jobModel::getTriggerGroup();
timeout=0
cid=16850

- 执行jobTest模块的getTriggerGroupTest方法，参数是'tag', array  @2
- 执行jobTest模块的getTriggerGroupTest方法，参数是'commit', array  @2
- 执行jobTest模块的getTriggerGroupTest方法，参数是'nonexistent', array  @0
- 执行jobTest模块的getTriggerGroupTest方法，参数是'schedule', array  @2
- 执行jobTest模块的getTriggerGroupTest方法，参数是'tag', array  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('job');
$table->id->range('1-10');
$table->name->range('Job1,Job2,Job3,Job4,Job5,Job6,Job7,Job8,Job9,Job10');
$table->repo->range('1,1,1,2,2,2,3,3,4,4');
$table->triggerType->range('tag,tag,tag,tag,commit,commit,commit,schedule,schedule,manual');
$table->deleted->range('0');
$table->gen(10);

su('admin');

$jobTest = new jobModelTest();

r(count($jobTest->getTriggerGroupTest('tag', array(1, 2)))) && p() && e('2');
r(count($jobTest->getTriggerGroupTest('commit', array(1, 2, 3)))) && p() && e('2');
r(count($jobTest->getTriggerGroupTest('nonexistent', array(1, 2)))) && p() && e('0');
r(count($jobTest->getTriggerGroupTest('schedule', array()))) && p() && e('2');
r(count($jobTest->getTriggerGroupTest('tag', array(999)))) && p() && e('0');