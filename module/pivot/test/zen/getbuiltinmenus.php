#!/usr/bin/env php
<?php

/**

title=测试 pivotZen::getBuiltinMenus();
timeout=0
cid=0

- 执行pivotTest模块的getBuiltinMenusTest方法，参数是'normal_case'  @2
- 执行pivotTest模块的getBuiltinMenusTest方法，参数是'empty_pivot_list'  @0
- 执行pivotTest模块的getBuiltinMenusTest方法，参数是'no_permission'  @0
- 执行pivotTest模块的getBuiltinMenusTest方法，参数是'invalid_format'  @0
- 执行pivotTest模块的getBuiltinMenusTest方法，参数是'multiple_valid_items'  @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

su('admin');

$pivotTest = new pivotTest();

r(count($pivotTest->getBuiltinMenusTest('normal_case'))) && p() && e('2');
r(count($pivotTest->getBuiltinMenusTest('empty_pivot_list'))) && p() && e('0');
r(count($pivotTest->getBuiltinMenusTest('no_permission'))) && p() && e('0');
r(count($pivotTest->getBuiltinMenusTest('invalid_format'))) && p() && e('0');
r(count($pivotTest->getBuiltinMenusTest('multiple_valid_items'))) && p() && e('5');