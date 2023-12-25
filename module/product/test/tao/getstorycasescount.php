#!/usr/bin/env php
<?php

/**

title=测试productModel->getStoryCasesCount();
cid=0

- 测试获取需求列表1所关联的用例总数 @30
- 测试获取需求列表2所关联的用例总数 @30
- 测试获取需求列表3所关联的用例总数 @26
- 测试获取需求列表4所关联的用例总数 @50
- 测试获取需求列表5所关联的用例总数 @50
- 测试获取空需求列表所关联的用例总数 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('case')->config('case')->gen(50);

$productTao = $tester->loadModel('product');

$storyIdList1 = range(0, 5);
$storyIdList2 = range(1, 5);
$storyIdList3 = range(5, 10);
$storyIdList4 = range(1, 10);
$storyIdList5 = range(-1, 10);
$emptyList    = array();

r($productTao->getStoryCasesCount($storyIdList1)) && p() && e('30'); // 测试获取需求列表1所关联的用例总数
r($productTao->getStoryCasesCount($storyIdList2)) && p() && e('30'); // 测试获取需求列表2所关联的用例总数
r($productTao->getStoryCasesCount($storyIdList3)) && p() && e('26'); // 测试获取需求列表3所关联的用例总数
r($productTao->getStoryCasesCount($storyIdList4)) && p() && e('50'); // 测试获取需求列表4所关联的用例总数
r($productTao->getStoryCasesCount($storyIdList5)) && p() && e('50'); // 测试获取需求列表5所关联的用例总数
r($productTao->getStoryCasesCount($emptyList))    && p() && e('0');  // 测试获取空需求列表所关联的用例总数
