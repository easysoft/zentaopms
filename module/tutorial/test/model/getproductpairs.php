#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 tutorialModel::getProductPairs();
timeout=0
cid=19450

- 步骤1：测试获取产品ID为1的产品名称属性1 @Test product
- 步骤2：测试返回数组不为空 @0
- 步骤3：测试返回数组包含1个元素 @1
- 步骤4：测试数组键为1 @1
- 步骤5：测试返回值类型为数组 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

zenData('user')->gen(5);

su('admin');

$tutorial = new tutorialTest();

r($tutorial->getProductPairsTest()) && p('1') && e('Test product'); // 步骤1：测试获取产品ID为1的产品名称
r(empty($tutorial->getProductPairsTest())) && p() && e('0'); // 步骤2：测试返回数组不为空
r(count($tutorial->getProductPairsTest())) && p() && e('1'); // 步骤3：测试返回数组包含1个元素
r(array_keys($tutorial->getProductPairsTest())) && p('0') && e('1'); // 步骤4：测试数组键为1
r(is_array($tutorial->getProductPairsTest())) && p() && e('1'); // 步骤5：测试返回值类型为数组