#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::checkProducts();
timeout=0
cid=0

- 步骤1:qa tab,有产品,成功执行
 - 属性success @1
 - 属性tab @qa
- 步骤2:project tab,有产品,成功执行
 - 属性success @1
 - 属性tab @project
- 步骤3:execution tab,有产品,成功执行
 - 属性success @1
 - 属性tab @execution
- 步骤4:qa tab,无产品,成功执行
 - 属性success @1
 - 属性hasProducts @0
- 步骤5:project tab,无产品,成功执行
 - 属性success @1
 - 属性hasProducts @0
- 步骤6:execution tab,无产品,成功执行
 - 属性success @1
 - 属性hasProducts @0
- 步骤7:other tab,有产品,成功执行
 - 属性success @1
 - 属性tab @other

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

su('admin');

$testcaseTest = new testcaseZenTest();

r($testcaseTest->checkProductsTest('qa', 0, 0, true)) && p('success;tab') && e('1;qa'); // 步骤1:qa tab,有产品,成功执行
r($testcaseTest->checkProductsTest('project', 1, 0, true)) && p('success;tab') && e('1;project'); // 步骤2:project tab,有产品,成功执行
r($testcaseTest->checkProductsTest('execution', 0, 1, true)) && p('success;tab') && e('1;execution'); // 步骤3:execution tab,有产品,成功执行
r($testcaseTest->checkProductsTest('qa', 0, 0, false)) && p('success;hasProducts') && e('1;0'); // 步骤4:qa tab,无产品,成功执行
r($testcaseTest->checkProductsTest('project', 1, 0, false)) && p('success;hasProducts') && e('1;0'); // 步骤5:project tab,无产品,成功执行
r($testcaseTest->checkProductsTest('execution', 0, 1, false)) && p('success;hasProducts') && e('1;0'); // 步骤6:execution tab,无产品,成功执行
r($testcaseTest->checkProductsTest('other', 0, 0, true)) && p('success;tab') && e('1;other'); // 步骤7:other tab,有产品,成功执行