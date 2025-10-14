#!/usr/bin/env php
<?php

/**

title=测试 productZen::getBackLink4Create();
timeout=0
cid=0

- 执行productTest模块的getBackLink4CreateTest方法，参数是'from=qa'  @/qa/index
- 执行productTest模块的getBackLink4CreateTest方法，参数是'from=global'  @/product/all
- 执行productTest模块的getBackLink4CreateTest方法，参数是'other=value'  @0
- 执行productTest模块的getBackLink4CreateTest方法，参数是'param1=value1, from=qa, param2=value2'  @/qa/index
- 执行productTest模块的getBackLink4CreateTest方法，参数是'param1=value1, from=global, param2 = value2'  @/product/all

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

su('admin');

$productTest = new productTest();

r($productTest->getBackLink4CreateTest('from=qa')) && p() && e('/qa/index');
r($productTest->getBackLink4CreateTest('from=global')) && p() && e('/product/all');
r($productTest->getBackLink4CreateTest('other=value')) && p() && e('0');
r($productTest->getBackLink4CreateTest('param1=value1, from=qa, param2=value2')) && p() && e('/qa/index');
r($productTest->getBackLink4CreateTest('param1=value1, from=global, param2 = value2')) && p() && e('/product/all');