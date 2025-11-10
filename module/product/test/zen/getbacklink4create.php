#!/usr/bin/env php
<?php

/**

title=测试 productZen::getBackLink4Create();
timeout=0
cid=0

- 测试步骤1:extra参数为空字符串 @0
- 测试步骤2:extra参数from=qa @1
- 测试步骤3:extra参数from=global @1
- 测试步骤4:extra参数from=other @0
- 测试步骤5:extra参数包含逗号和空格 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$productTest = new productZenTest();

r($productTest->getBackLink4CreateTest('')) && p() && e('0'); // 测试步骤1:extra参数为空字符串
r(strpos($productTest->getBackLink4CreateTest('from=qa'), 'm=qa&f=index') !== false) && p() && e('1'); // 测试步骤2:extra参数from=qa
r(strpos($productTest->getBackLink4CreateTest('from=global'), 'm=product&f=all') !== false) && p() && e('1'); // 测试步骤3:extra参数from=global
r($productTest->getBackLink4CreateTest('from=other')) && p() && e('0'); // 测试步骤4:extra参数from=other
r(strpos($productTest->getBackLink4CreateTest('from=qa, param=1'), 'm=qa&f=index') !== false) && p() && e('1'); // 测试步骤5:extra参数包含逗号和空格