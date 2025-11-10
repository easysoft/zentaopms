#!/usr/bin/env php
<?php

/**

title=测试 pivotZen::getSidebarMenus();
timeout=0
cid=0

- 执行pivotTest模块的getSidebarMenusTest方法，参数是1, 1  @0
- 执行pivotTest模块的getSidebarMenusTest方法，参数是1, 999  @0
- 执行pivotTest模块的getSidebarMenusTest方法，参数是1, 5  @0
- 执行pivotTest模块的getSidebarMenusTest方法，参数是2, 2  @0
- 执行pivotTest模块的getSidebarMenusTest方法，参数是1, 1  @1
- 执行pivotTest模块的getSidebarMenusTest方法，参数是1, 2  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivotzen.unittest.class.php';

zenData('module')->loadYaml('tree_getsidebarmenus', false, 2)->gen(4);
zenData('pivot')->loadYaml('pivot_getsidebarmenus', false, 2)->gen(20);
zenData('dimension')->loadYaml('dimension_getsidebarmenus', false, 2)->gen(3);
zenData('user')->gen(5);
zenData('dataset')->gen(10);
zenData('pivotspec')->gen(20);

su('admin');

global $app;
$app->loadLang('pivot');

$pivotTest = new pivotZenTest();

r(count($pivotTest->getSidebarMenusTest(1, 1))) && p() && e('0');
r(count($pivotTest->getSidebarMenusTest(1, 999))) && p() && e('0');
r(count($pivotTest->getSidebarMenusTest(1, 5))) && p() && e('0');
r(count($pivotTest->getSidebarMenusTest(2, 2))) && p() && e('0');
r(is_array($pivotTest->getSidebarMenusTest(1, 1))) && p() && e('1');
r(is_array($pivotTest->getSidebarMenusTest(1, 2))) && p() && e('1');