#!/usr/bin/env php
<?php

/**

title=测试 actionTao::processCreateChildrenActionExtra();
cid=0

- 测试单个有效任务ID >> 生成包含链接的子任务信息
- 测试多个有效任务ID >> 生成多个子任务链接
- 测试空字符串 >> 返回空结果
- 测试不存在的任务ID >> 返回空结果
- 测试混合有效和无效任务ID >> 只生成有效任务的链接

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

$task = zenData('task');
$task->id->range('1-10');
$task->name->range('子任务1,子任务2,子任务3,子任务4,子任务5,子任务6,子任务7,子任务8,子任务9,子任务10');
$task->project->range('1');
$task->execution->range('1');
$task->assignedTo->range('admin');
$task->status->range('wait');
$task->deleted->range('0');
$task->gen(10);

su('admin');

$actionTest = new actionTest();

r($actionTest->processCreateChildrenActionExtraTest('1')) && p('extra') && e('#1 子任务1');
r($actionTest->processCreateChildrenActionExtraTest('1,2,3')) && p('extra') && e('#1 子任务1, #2 子任务2, #3 子任务3');
r($actionTest->processCreateChildrenActionExtraTest('')) && p('extra') && e('');
r($actionTest->processCreateChildrenActionExtraTest('999')) && p('extra') && e('');
r($actionTest->processCreateChildrenActionExtraTest('1,999,2')) && p('extra') && e('#1 子任务1, #2 子任务2');