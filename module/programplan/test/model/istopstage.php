#!/usr/bin/env php
<?php

/**

title=测试programplanModel->isTopStage();
cid=0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/programplan.class.php';

zdTable('user')->gen(5);
su('admin');

zdTable('project')->config('istopstage')->gen(5);

$plan = new programplanTest();

r($plan->isTopStageTest(2)) && p('') && e('1'); // 测试id为2判断是否为顶级阶段
r($plan->isTopStageTest(4)) && p('') && e('0'); // 测试id为4判断是否为顶级阶段
r($plan->isTopStageTest(5)) && p('') && e('1'); // 测试id为5判断是否为顶级阶段
