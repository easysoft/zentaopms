#!/usr/bin/env php
<?php

/**

title=测试 productZen::getCreatedLocate();
timeout=0
cid=17577

- 测试步骤1:在product标签页创建产品,programID为0
 - 属性result @success
 - 属性closeModal @1
- 测试步骤2:在program标签页创建产品,programID不为0属性result @success
- 测试步骤3:在doc标签页创建产品属性result @success
- 测试步骤4:在product标签页创建产品,programID不为0属性result @success
- 测试步骤5:在其他标签页创建产品,programID为0
 - 属性result @success
 - 属性closeModal @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$productTest = new productZenTest();

r($productTest->getCreatedLocateTest(1, 0, 'product', false)) && p('result,closeModal') && e('success,1'); // 测试步骤1:在product标签页创建产品,programID为0
r($productTest->getCreatedLocateTest(1, 10, 'program', false)) && p('result') && e('success'); // 测试步骤2:在program标签页创建产品,programID不为0
r($productTest->getCreatedLocateTest(1, 0, 'doc', false)) && p('result') && e('success'); // 测试步骤3:在doc标签页创建产品
r($productTest->getCreatedLocateTest(1, 10, 'product', false)) && p('result') && e('success'); // 测试步骤4:在product标签页创建产品,programID不为0
r($productTest->getCreatedLocateTest(1, 0, 'other', false)) && p('result,closeModal') && e('success,1'); // 测试步骤5:在其他标签页创建产品,programID为0