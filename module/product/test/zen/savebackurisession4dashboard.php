#!/usr/bin/env php
<?php

/**

title=测试 productZen::saveBackUriSession4Dashboard();
timeout=0
cid=0

- 执行productTest模块的saveBackUriSession4DashboardTest方法，参数是1  @void
- 执行productTest模块的saveBackUriSession4DashboardTest方法，参数是2  @not empty
- 执行productTest模块的saveBackUriSession4DashboardTest方法，参数是3  @not empty
- 执行productTest模块的saveBackUriSession4DashboardTest方法，参数是4  @true
- 执行productTest模块的saveBackUriSession4DashboardTest方法，参数是5  @true

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

su('admin');

$productTest = new productTest();

r($productTest->saveBackUriSession4DashboardTest(1)) && p() && e('void');
r($productTest->saveBackUriSession4DashboardTest(2)) && p() && e('not empty');
r($productTest->saveBackUriSession4DashboardTest(3)) && p() && e('not empty');
r($productTest->saveBackUriSession4DashboardTest(4)) && p() && e('true');
r($productTest->saveBackUriSession4DashboardTest(5)) && p() && e('true');