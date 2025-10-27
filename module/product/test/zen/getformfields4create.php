#!/usr/bin/env php
<?php

/**

title=测试 productZen::getFormFields4Create();
timeout=0
cid=0

- 执行productTest模块的getFormFields4CreateTest方法，参数是1, '' 第program条的default属性 @1
- 执行productTest模块的getFormFields4CreateTest方法，参数是0, '' 第program条的default属性 @~~
- 执行productTest模块的getFormFields4CreateTest方法，参数是1, 'name=testProduct, type=normal' 第name条的default属性 @testProduct
- 执行productTest模块的getFormFields4CreateTest方法，参数是1, '' 第name条的control属性 @text
- 执行productTest模块的getFormFields4CreateTest方法，参数是1, 'name=test product, type=branch' 第type条的default属性 @branch

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

zenData('user')->gen(10);

su('admin');

$productTest = new productTest();

r($productTest->getFormFields4CreateTest(1, '')) && p('program:default') && e('1');
r($productTest->getFormFields4CreateTest(0, '')) && p('program:default') && e('~~');
r($productTest->getFormFields4CreateTest(1, 'name=testProduct,type=normal')) && p('name:default') && e('testProduct');
r($productTest->getFormFields4CreateTest(1, '')) && p('name:control') && e('text');
r($productTest->getFormFields4CreateTest(1, 'name=test product, type=branch')) && p('type:default') && e('branch');