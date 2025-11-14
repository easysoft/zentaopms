#!/usr/bin/env php
<?php

/**

title=测试 commonModel::sortFeatureMenu();
timeout=0
cid=15716

- 执行commonTest模块的sortFeatureMenuTest方法，参数是1  @1
- 执行commonTest模块的sortFeatureMenuTest方法，参数是2  @1
- 执行commonTest模块的sortFeatureMenuTest方法，参数是3  @1
- 执行commonTest模块的sortFeatureMenuTest方法，参数是4  @2
- 执行commonTest模块的sortFeatureMenuTest方法，参数是5  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

su('admin');

$commonTest = new commonTest();

r($commonTest->sortFeatureMenuTest(1)) && p() && e('1');
r($commonTest->sortFeatureMenuTest(2)) && p() && e('1');
r($commonTest->sortFeatureMenuTest(3)) && p() && e('1');
r($commonTest->sortFeatureMenuTest(4)) && p() && e('2');
r($commonTest->sortFeatureMenuTest(5)) && p() && e('1');