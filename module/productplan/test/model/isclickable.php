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

$planTester = new productplanModelTest('admin');

zenData('productplan')->loadYaml('productplan')->gen(20);

$planID = array();
$planID[] = 2;
$planID[] = 1;
$planID[] = 4;
$planID[] = 3;
$planID[] = 4;

$action = array();
$action[] = 'start';
$action[] = 'finish';
$action[] = 'close';
$action[] = 'activate';
$action[] = 'start';

r($planTester->isClickable($planID[0], $action[0])) && p() && e('0'); //检查开始按钮
r($planTester->isClickable($planID[1], $action[1])) && p() && e('0'); //检查完成按钮
r($planTester->isClickable($planID[2], $action[2])) && p() && e('0'); //检查关闭按钮
r($planTester->isClickable($planID[3], $action[3])) && p() && e('1'); //检查激活按钮
r($planTester->isClickable($planID[4], $action[4])) && p() && e('0'); //检查开始按钮