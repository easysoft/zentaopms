#!/usr/bin/env php
<?php

/**

title=测试 actionTao::processCreateChildrenActionExtra();
timeout=0
cid=0

- 测试单个任务ID(ID为1) @1
- 测试多个任务ID(ID为1,2,3) @1
- 测试单个任务ID(ID为2) @1
- 测试两个任务ID(ID为3,4) @1
- 测试单个任务ID(ID为5) @1
- 测试边界值任务ID(ID为19,20) @1
- 测试空字符串输入 @1
- 测试不存在的任务ID(ID为999) @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$taskTable = zenData('task');
$taskTable->id->range('1-20');
$taskTable->name->range('任务1,测试任务2,开发任务3,Bug修复4,代码审查5,单元测试6,接口开发7,前端实现8,数据库设计9,需求分析10,设计评审11,性能优化12,代码重构13,文档编写14,测试用例15,集成测试16,上线部署17,问题修复18,功能开发19,任务管理20');
$taskTable->parent->range('0');
$taskTable->project->range('1');
$taskTable->execution->range('1');
$taskTable->story->range('0');
$taskTable->type->range('devel');
$taskTable->status->range('wait');
$taskTable->assignedTo->range('admin');
$taskTable->gen(20);

su('admin');

$actionTest = new actionTaoTest();

r(strpos($actionTest->processCreateChildrenActionExtraTest('1')->extra, '#1') !== false && strpos($actionTest->processCreateChildrenActionExtraTest('1')->extra, '任务1') !== false) && p() && e('1'); // 测试单个任务ID(ID为1)
r(strpos($actionTest->processCreateChildrenActionExtraTest('1,2,3')->extra, '#1') !== false && strpos($actionTest->processCreateChildrenActionExtraTest('1,2,3')->extra, '#2') !== false && strpos($actionTest->processCreateChildrenActionExtraTest('1,2,3')->extra, '#3') !== false) && p() && e('1'); // 测试多个任务ID(ID为1,2,3)
r(strpos($actionTest->processCreateChildrenActionExtraTest('2')->extra, '#2') !== false && strpos($actionTest->processCreateChildrenActionExtraTest('2')->extra, '测试任务2') !== false) && p() && e('1'); // 测试单个任务ID(ID为2)
r(strpos($actionTest->processCreateChildrenActionExtraTest('3,4')->extra, '#3') !== false && strpos($actionTest->processCreateChildrenActionExtraTest('3,4')->extra, '#4') !== false) && p() && e('1'); // 测试两个任务ID(ID为3,4)
r(strpos($actionTest->processCreateChildrenActionExtraTest('5')->extra, '#5') !== false && strpos($actionTest->processCreateChildrenActionExtraTest('5')->extra, '代码审查5') !== false) && p() && e('1'); // 测试单个任务ID(ID为5)
r(strpos($actionTest->processCreateChildrenActionExtraTest('19,20')->extra, '#19') !== false && strpos($actionTest->processCreateChildrenActionExtraTest('19,20')->extra, '#20') !== false) && p() && e('1'); // 测试边界值任务ID(ID为19,20)
r($actionTest->processCreateChildrenActionExtraTest('')->extra == '') && p() && e('1'); // 测试空字符串输入
r($actionTest->processCreateChildrenActionExtraTest('999')->extra == '') && p() && e('1'); // 测试不存在的任务ID(ID为999)