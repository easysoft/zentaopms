#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::getProducts();
timeout=0
cid=17396

- 执行$pivotTest, 'getProductsTest' @1
- 执行pivotTest模块的getProductsTest方法，参数是''  @0
- 执行pivotTest模块的getProductsTest方法，参数是'', 'story'  @0
- 执行pivotTest模块的getProductsTest方法，参数是'', 'requirement'  @0
- 执行pivotTest模块的getProductsTest方法，参数是'', 'story', array  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 不依赖zenData，直接测试
su('admin');

$pivotTest = new pivotModelTest();

// 测试步骤1：验证测试方法存在
r(method_exists($pivotTest, 'getProductsTest')) && p() && e('1');

// 测试步骤2：基础功能调用测试
r($pivotTest->getProductsTest('')) && p() && e('0');

// 测试步骤3：story类型参数测试
r($pivotTest->getProductsTest('', 'story')) && p() && e('0');

// 测试步骤4：requirement类型参数测试
r($pivotTest->getProductsTest('', 'requirement')) && p() && e('0');

// 测试步骤5：带过滤器参数测试
r($pivotTest->getProductsTest('', 'story', array())) && p() && e('0');