#!/usr/bin/env php
<?php

/**

title=测试 programplanModel::isClickable();
cid=0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('project')->config('project')->gen(5);
zdTable('project')->config('stage')->gen(5, $isClear = false);

$task = zdTable('task');
$task->execution->range('1-10');
$task->gen(10);

global $tester;
$programplan = $tester->loadModel('programplan');
$emptyStage  = new stdclass();
$stage       = $programplan->getByID(6);

r($programplan::isClickable($emptyStage, $action = 'close'))  && p() && e('1'); // 判断阶段为空时，是否有关闭的点击操作权限
r($programplan::isClickable($emptyStage, $action = 'create')) && p() && e('1'); // 判断阶段为空时，是否有创建的点击操作权限
r($programplan::isClickable($stage,      $action = 'close'))  && p() && e('1'); // 判断阶段不为空时，是否有关闭的点击操作权限
r($programplan::isClickable($stage,      $action = 'create')) && p() && e('0'); // 判断阶段不为空并且该阶段下有任务，是否有创建的点击操作权限
