#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('project')->config('execution')->gen(400);

/**

title=测试 commonModel->syncExecutionByChild();
timeout=0
cid=1

- 执行$execution1属性status @wait
- 执行$execution2属性status @doing
- 执行$execution3属性status @wait
- 没有父阶段的数据不会被更新 @0
- 没有父阶段的数据不会被更新 @0
- 没有父阶段的数据不会被更新 @0

*/

global $tester;
$tester->loadModel('execution');
$tester->loadModel('common');

$execution1 = $tester->execution->getById(301);
$execution2 = $tester->execution->getById(302);
$execution3 = $tester->execution->getById(303);

r($execution1) && p('status') && e('wait');
r($execution2) && p('status') && e('doing');
r($execution3) && p('status') && e('wait');

$parentExectuion1 = $tester->common->syncExecutionByChild($execution1);
$parentExectuion2 = $tester->common->syncExecutionByChild($execution2);
$parentExectuion3 = $tester->common->syncExecutionByChild($execution3);

r($parentExectuion1) && p('') && e('0'); // 没有父阶段的数据不会被更新
r($parentExectuion2) && p('') && e('0'); // 没有父阶段的数据不会被更新
r($parentExectuion3) && p('') && e('0'); // 没有父阶段的数据不会被更新