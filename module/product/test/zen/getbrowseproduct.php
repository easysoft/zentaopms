#!/usr/bin/env php
<?php

/**

title=测试 productZen::getBrowseProduct();
timeout=0
cid=17576

- 执行productTest模块的getBrowseProductTest方法，参数是1 属性name @产品1
- 执行productTest模块的getBrowseProductTest方法，参数是2 属性name @产品2
- 执行productTest模块的getBrowseProductTest方法，参数是9999  @0
- 执行productTest模块的getBrowseProductTest方法  @0
- 执行productTest模块的getBrowseProductTest方法，参数是3 属性name @产品3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('product')->loadYaml('product', false, 2)->gen(10);

su('admin');

$productTest = new productZenTest();

r($productTest->getBrowseProductTest(1)) && p('name') && e('产品1');
r($productTest->getBrowseProductTest(2)) && p('name') && e('产品2');
r($productTest->getBrowseProductTest(9999)) && p() && e('0');
r($productTest->getBrowseProductTest(0)) && p() && e('0');
r($productTest->getBrowseProductTest(3)) && p('name') && e('产品3');