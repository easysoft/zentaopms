#!/usr/bin/env php
<?php

/**

title=测试 pivotTao::getFirstGroup();
timeout=0
cid=17442

- 执行pivotTest模块的getFirstGroupTest方法，参数是1  @1
- 执行pivotTest模块的getFirstGroupTest方法，参数是2  @4
- 执行pivotTest模块的getFirstGroupTest方法，参数是3  @7
- 执行pivotTest模块的getFirstGroupTest方法，参数是999  @0
- 执行pivotTest模块的getFirstGroupTest方法  @0
- 执行pivotTest模块的getFirstGroupTest方法，参数是-1  @0
- 执行pivotTest模块的getFirstGroupTest方法，参数是PHP_INT_MAX  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

zenData('module')->loadYaml('module_pivot')->gen(9);

su('admin');

$pivotTest = new pivotTest();

r($pivotTest->getFirstGroupTest(1)) && p('') && e('1');
r($pivotTest->getFirstGroupTest(2)) && p('') && e('4');
r($pivotTest->getFirstGroupTest(3)) && p('') && e('7');
r($pivotTest->getFirstGroupTest(999)) && p('') && e('0');
r($pivotTest->getFirstGroupTest(0)) && p('') && e('0');
r($pivotTest->getFirstGroupTest(-1)) && p('') && e('0');
r($pivotTest->getFirstGroupTest(PHP_INT_MAX)) && p('') && e('0');