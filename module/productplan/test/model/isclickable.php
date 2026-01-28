#!/usr/bin/env php
<?php
/**

title=productplanModel->isClickable();
timeout=0
cid=17641

- 检查开始按钮 @0
- 检查完成按钮 @0
- 检查关闭按钮 @0
- 检查激活按钮 @1
- 检查开始按钮 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('productplan')->loadYaml('productplan')->gen(20);
$plan = new productPlan('admin');

$planID = array();
$planID[] = 10;
$planID[] = 1;
$planID[] = 12;
$planID[] = 15;
$planID[] = 16;

$action = array();
$action[] = 'start';
$action[] = 'finish';
$action[] = 'close';
$action[] = 'activate';
$action[] = 'start';

r($plan->isClickable($planID[0], $action[0])) && p() && e('0'); //检查开始按钮
r($plan->isClickable($planID[1], $action[1])) && p() && e('0'); //检查完成按钮
r($plan->isClickable($planID[2], $action[2])) && p() && e('0'); //检查关闭按钮
r($plan->isClickable($planID[3], $action[3])) && p() && e('1'); //检查激活按钮
r($plan->isClickable($planID[4], $action[4])) && p() && e('0'); //检查开始按钮
