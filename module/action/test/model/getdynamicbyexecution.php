#!/usr/bin/env php
<?php

/**

title=测试 actionModel::getDynamicByExecution();
timeout=0
cid=14895

- 执行actionTest模块的getDynamicByExecutionTest方法，参数是1  @0
- 执行actionTest模块的getDynamicByExecutionTest方法，参数是999  @0
- 执行actionTest模块的getDynamicByExecutionTest方法，参数是1, 'admin'  @0
- 执行actionTest模块的getDynamicByExecutionTest方法，参数是1, 'all', 'today'  @0
- 执行actionTest模块的getDynamicByExecutionTest方法，参数是1, 'all', 'all', '', 'next'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

ob_start();
zenData('action')->gen(10);
zenData('user')->gen(5);
zenData('project')->gen(5);
ob_end_clean();

su('admin');

$actionTest = new actionModelTest();

r($actionTest->getDynamicByExecutionTest(1)) && p() && e('0');
r($actionTest->getDynamicByExecutionTest(999)) && p() && e('0');
r($actionTest->getDynamicByExecutionTest(1, 'admin')) && p() && e('0');
r($actionTest->getDynamicByExecutionTest(1, 'all', 'today')) && p() && e('0');
r($actionTest->getDynamicByExecutionTest(1, 'all', 'all', '', 'next')) && p() && e('0');