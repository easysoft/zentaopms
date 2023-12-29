#!/usr/bin/env php
<?php

/**

title=测试programplanModel->getSiblings();
cid=0

- 测试id为2时获取兄弟阶段的的名称属性name @阶段b
- 测试id为2时获取自己兄弟阶段的个数 @1
- 测试id为4时获取自己兄弟阶段的个数 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/programplan.class.php';
su('admin');

zdTable('project')->config('getsiblings')->gen(5);
$plan = new programplanTest();

$topPlan      = $plan->getSiblingsTest(2);
$topPlanCount = count($topPlan[2]);

$leafPlan      = $plan->getSiblingsTest(4);
$leafPlanCount = count($leafPlan[4]);

r($topPlan[2][5])     && p('name') && e('阶段b'); // 测试id为2时获取兄弟阶段的的名称
r($topPlanCount - 1)  && p('')     && e(1);       // 测试id为2时获取自己兄弟阶段的个数
r($leafPlanCount - 1) && p('')     && e(0);       // 测试id为4时获取自己兄弟阶段的个数
