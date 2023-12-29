#!/usr/bin/env php
<?php

/**

title=测试programplanModel->getSelfAndChildrenList();
cid=0

- 测试id为2时获取自己的状态属性status @doing
- 测试id为2时获取自己后代的个数 @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/programplan.class.php';
su('admin');

zdTable('project')->config('getselfandchildrenlist')->gen(6);

$plan    = new programplanTest();
$topPlan = $plan->getSelfAndChildrenListTest(2);

$topPlanCount = count($topPlan[2]);

r($topPlan[2][2])     && p('status') && e('doing'); // 测试id为2时获取自己的状态
r($topPlanCount - 1)  && p('')       && e(3);       // 测试id为2时获取自己后代的个数
