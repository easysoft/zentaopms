#!/usr/bin/env php
<?php

/**

title=测试 productZen::saveBackUriSessionForDynamic();
timeout=0
cid=0

- 执行productTest模块的saveBackUriSessionForDynamicTest方法 属性result @1
- 执行productTest模块的saveBackUriSessionForDynamicTest方法 属性productList @/test/uri
- 执行productTest模块的saveBackUriSessionForDynamicTest方法 属性productPlanList @/test/uri
- 执行productTest模块的saveBackUriSessionForDynamicTest方法 属性releaseList @/test/uri
- 执行productTest模块的saveBackUriSessionForDynamicTest方法 属性sessionCount @11

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

$productTest = new productTest();

r($productTest->saveBackUriSessionForDynamicTest()) && p('result') && e('1');
r($productTest->saveBackUriSessionForDynamicTest()) && p('productList') && e('/test/uri');
r($productTest->saveBackUriSessionForDynamicTest()) && p('productPlanList') && e('/test/uri');
r($productTest->saveBackUriSessionForDynamicTest()) && p('releaseList') && e('/test/uri');
r($productTest->saveBackUriSessionForDynamicTest()) && p('sessionCount') && e('11');