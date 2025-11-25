#!/usr/bin/env php
<?php

/**

title=测试 productZen::getProductLines();
timeout=0
cid=17590

- 步骤1:多个项目集ID,返回数组包含2个元素 @2
- 步骤2:单个项目集ID,返回数组长度为2 @2
- 步骤3:空数组,返回数组长度为2 @2
- 步骤4:不存在的项目集ID,第一个元素为空 @0
- 步骤5:返回数组长度为2 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zendata('module')->loadYaml('getproductlines/module', false, 2)->gen(10);

su('admin');

$productTest = new productZenTest();

r(count($productTest->getProductLinesTest(array(1, 2)))) && p() && e('2'); // 步骤1:多个项目集ID,返回数组包含2个元素
r(count($productTest->getProductLinesTest(array(1)))) && p() && e('2'); // 步骤2:单个项目集ID,返回数组长度为2
r(count($productTest->getProductLinesTest(array()))) && p() && e('2'); // 步骤3:空数组,返回数组长度为2
r(count($productTest->getProductLinesTest(array(999))[0])) && p() && e('0'); // 步骤4:不存在的项目集ID,第一个元素为空
r(count($productTest->getProductLinesTest(array(1, 2, 3)))) && p() && e('2'); // 步骤5:返回数组长度为2