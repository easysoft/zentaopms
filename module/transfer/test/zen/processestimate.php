#!/usr/bin/env php
<?php

/**

title=测试 transferZen::processEstimate();
timeout=0
cid=19340

- 执行transferTest模块的processEstimateTest方法，参数是1,   @1
- 执行transferTest模块的processEstimateTest方法，参数是2,   @1
- 执行transferTest模块的processEstimateTest方法，参数是3,   @1
- 执行transferTest模块的processEstimateTest方法，参数是4, null), "<input type='text' name='estimate[4]'") !== false  @1
- 执行transferTest模块的processEstimateTest方法，参数是5,   @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/transferzen.unittest.class.php';

zenData('user')->gen(10);
zenData('team')->gen(5);

su('admin');

$transferTest = new transferZenTest();

r(strpos($transferTest->processEstimateTest(1, (object)array('estimate' => '')), "<input type='text' name='estimate[1]'") !== false) && p() && e('1');
r(strpos($transferTest->processEstimateTest(2, (object)array('estimate' => array('admin' => 8, 'user1' => 16))), "<table class='table-borderless'>") !== false) && p() && e('1');
r(strpos($transferTest->processEstimateTest(3, (object)array('estimate' => 24)), "<input type='text' name='estimate[3]'") !== false) && p() && e('1');
r(strpos($transferTest->processEstimateTest(4, null), "<input type='text' name='estimate[4]'") !== false) && p() && e('1');
r(strpos($transferTest->processEstimateTest(5, (object)array('estimate' => 0)), "value=''") !== false) && p() && e('1');