#!/usr/bin/env php
<?php
/**

title=productplanModel->isClickable();
timeout=0
cid=1

- 执行plan模块的isClickable方法，参数是$planID[0], $action[0]  @0
- 执行plan模块的isClickable方法，参数是$planID[1], $action[1]  @0
- 执行plan模块的isClickable方法，参数是$planID[2], $action[2]  @0
- 执行plan模块的isClickable方法，参数是$planID[3], $action[3]  @1
- 执行plan模块的isClickable方法，参数是$planID[3], $action[3]  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/productplan.unittest.class.php';

zenData('productplan')->loadYaml('productplan')->gen(20);
$plan = new productPlan('admin');

$planID = array();
$planID[] = 10;
$planID[] = 1;
$planID[] = 12;
$planID[] = 15;

$action = array();
$action[] = 'start';
$action[] = 'finish';
$action[] = 'close';
$action[] = 'activate';

r($plan->isClickable($planID[0], $action[0])) && p() && e('0');
r($plan->isClickable($planID[1], $action[1])) && p() && e('0');
r($plan->isClickable($planID[2], $action[2])) && p() && e('0');
r($plan->isClickable($planID[3], $action[3])) && p() && e('1');
