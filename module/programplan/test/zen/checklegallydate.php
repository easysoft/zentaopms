#!/usr/bin/env php
<?php

/**

title=测试 loadModel->checkLegallyDate()
timeout=0
cid=17791

- 不能超过项目的起止日期属性end[0] @阶段的结束时间不能大于所属项目的结束时间2025-02-01
- 没有错误 @0
- 不能超过父阶段起止日期属性end[0] @子阶段计划完成不能超过父阶段的计划完成时间 2025-01-15
- 不能超过父阶段起止日期属性begin[0] @子阶段计划开始不能小于父阶段的计划开始时间 2025-01-02
- 模板类型的项目没有错误 @0

*/

include dirname(__FILE__, 5). '/test/lib/init.php';
su('admin');

$project = new stdclass();
$project->begin = '2025-01-01';
$project->end   = '2025-02-01';

$plan = new stdclass();
$plan->begin = '2025-01-01';
$plan->end   = '2025-02-02';

global $tester;
$zen  = initReference('programplan');
$func = $zen->getMethod('checkLegallyDate');
$func->invokeArgs($zen->newInstance(), [$plan, $project, null, 0]);

r(dao::getError()) && p('end[0]') && e('阶段的结束时间不能大于所属项目的结束时间2025-02-01'); // 不能超过项目的起止日期

dao::$errors = array();

$project = new stdclass();
$project->begin = '2025-01-01';
$project->end   = '2025-02-01';

$plan = new stdclass();
$plan->begin = '2025-01-01';
$plan->end   = '2025-02-01';

$func->invokeArgs($zen->newInstance(), [$plan, $project, null, 0]);

r(dao::getError()) && p('') && e('0'); // 没有错误

dao::$errors = array();

$parent = new stdclass();
$parent->begin = '2025-01-01';
$parent->end   = '2025-01-15';

$func->invokeArgs($zen->newInstance(), [$plan, $project, $parent, 0]);

r(dao::getError()) && p('end[0]') && e('子阶段计划完成不能超过父阶段的计划完成时间 2025-01-15'); // 不能超过父阶段起止日期

dao::$errors = array();

$parent = new stdclass();
$parent->begin = '2025-01-02';
$parent->end   = '2025-02-15';

$func->invokeArgs($zen->newInstance(), [$plan, $project, $parent, 0]);

r(dao::getError()) && p('begin[0]') && e('子阶段计划开始不能小于父阶段的计划开始时间 2025-01-02'); // 不能超过父阶段起止日期

dao::$errors = array();

$project->isTpl = '1';

$func->invokeArgs($zen->newInstance(), [$plan, $project, $parent, 0]);

r(dao::getError()) && p('') && e('0'); // 模板类型的项目没有错误