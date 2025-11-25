#!/usr/bin/env php
<?php
/**

title=productpanModel->getBranchPlanPairs();
timeout=0
cid=17628

- 传入存在的数据，返回相应信息第2条的17属性 @计划17 [2021-06-01~2021-06-15]
- 传入存在的数据，返回相应信息第3条的18属性 @计划18 [2022-01-01~2022-01-30]
- 传入存在的数据，返回相应信息第4条的19属性 @计划19 [2022-07-01~2022-07-30]
- 传入存在的数据，返回相应信息第5条的20属性 @计划20 [2030-01-01~2030-01-01]
- 传入不存在的产品id @0
- 传入不存在的分支 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/productplan.unittest.class.php';

zenData('productplan')->loadYaml('productplan')->gen(20);
$plan = new productPlan('admin');

r($plan->getBranchPlanPairs(6, 2))     && p('2:17') && e('计划17 [2021-06-01~2021-06-15]'); //传入存在的数据，返回相应信息
r($plan->getBranchPlanPairs(6, 3))     && p('3:18') && e('计划18 [2022-01-01~2022-01-30]'); //传入存在的数据，返回相应信息
r($plan->getBranchPlanPairs(7, 4))     && p('4:19') && e('计划19 [2022-07-01~2022-07-30]'); //传入存在的数据，返回相应信息
r($plan->getBranchPlanPairs(7, 5))     && p('5:20') && e('计划20 [2030-01-01~2030-01-01]'); //传入存在的数据，返回相应信息
r($plan->getBranchPlanPairs(111, 2))   && p()       && e('0');                              //传入不存在的产品id
r($plan->getBranchPlanPairs(111, 111)) && p()       && e('0');                              //传入不存在的分支
