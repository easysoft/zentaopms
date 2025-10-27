#!/usr/bin/env php
<?php

/**

title=测试 productZen::getExportFields();
timeout=0
cid=0

- 执行productTest模块的getExportFieldsTest方法 属性fieldCount @7
- 执行productTest模块的getExportFieldsTest方法，参数是'light' 
 - 属性noProductLine @1
 - 属性noProgram @1
- 执行productTest模块的getExportFieldsTest方法，参数是'normal', true 属性hasExtendFields @1
- 执行productTest模块的getExportFieldsTest方法，参数是'normal', false, true 属性hasHeaderGroup @1
- 执行productTest模块的getExportFieldsTest方法 属性type @array

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

zenData('product')->gen(5);

su('admin');

$productTest = new productTest();

r($productTest->getExportFieldsTest()) && p('fieldCount') && e('7');
r($productTest->getExportFieldsTest('light')) && p('noProductLine,noProgram') && e('1,1');
r($productTest->getExportFieldsTest('normal', true)) && p('hasExtendFields') && e('1');
r($productTest->getExportFieldsTest('normal', false, true)) && p('hasHeaderGroup') && e('1');
r($productTest->getExportFieldsTest()) && p('type') && e('array');