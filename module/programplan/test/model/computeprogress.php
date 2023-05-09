#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/programplan.class.php';
su('admin');

function initData()
{
    zdTable('project')->config('project')->gen(9);
    zdTable('task')->config('task')->gen(9);
}

/**

title=测试 programplanModel->computeProgress();
timeout=0
cid=1

- 测试参数=项目id直接返回false @fail

- 测试阶段有子阶段 @success

- 测试阶段有子阶段状态更新为donging属性status @doing

*/

initData();

$programplan = new programplanTest();

r($programplan->computeProgressTest('3', 'edit', false))   && p() && e('fail'); // 测试参数=项目id直接返回false
r($programplan->computeProgressTest('4', 'edit', true))    && p() && e('success'); // 测试阶段有子阶段
r($programplan->getByIdTest(4))                            && p('status') && e('doing'); // 测试阶段有子阶段状态更新为donging
r($programplan->computeProgressTest('9', 'edit', true))    && p() && e('success'); // 测试子阶段有子阶段
r($programplan->getByIdTest(7))                            && p('status') && e('doing'); // 测试子阶段有子阶段状态更新为donging
