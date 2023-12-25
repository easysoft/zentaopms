#!/usr/bin/env php
<?php

/**

title=productModel->getProducts();
cid=0

- 测试获取项目11 状态为all的产品信息第1条的name属性 @正常产品1
- 测试获取项目12 状态为all的产品信息第2条的name属性 @正常产品2
- 测试获取项目13 状态为all的产品信息第3条的name属性 @正常产品3
- 测试获取项目14 状态为all的产品信息第4条的name属性 @正常产品4
- 测试获取项目15 状态为all的产品信息第5条的name属性 @正常产品5
- 测试获取不存在的项目状态为all的产品信息 @0
- 测试获取项目11 状态为unclosed的产品信息第1条的name属性 @正常产品1
- 测试获取项目12 状态为unclosed的产品信息第2条的name属性 @正常产品2
- 测试获取项目13 状态为unclosed的产品信息第3条的name属性 @正常产品3
- 测试获取项目14 状态为unclosed的产品信息第4条的name属性 @正常产品4
- 测试获取项目15 状态为unclosed的产品信息第5条的name属性 @正常产品5
- 测试获取不存在的项目状态为unclosed的产品信息 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('user')->gen(5);
zdTable('product')->gen(10);
zdTable('project')->gen(20);
zdTable('projectproduct')->gen(50);

$projectIdList = array(11, 12, 13, 14, 15, 1000001);
$statusList    = array('all', 'noclosed');

$product = new productTest('admin');
r($product->getProductsTest($projectIdList[0], $statusList[0])) && p('1:name') && e('正常产品1'); // 测试获取项目11 状态为all的产品信息
r($product->getProductsTest($projectIdList[1], $statusList[0])) && p('2:name') && e('正常产品2'); // 测试获取项目12 状态为all的产品信息
r($product->getProductsTest($projectIdList[2], $statusList[0])) && p('3:name') && e('正常产品3'); // 测试获取项目13 状态为all的产品信息
r($product->getProductsTest($projectIdList[3], $statusList[0])) && p('4:name') && e('正常产品4'); // 测试获取项目14 状态为all的产品信息
r($product->getProductsTest($projectIdList[4], $statusList[0])) && p('5:name') && e('正常产品5'); // 测试获取项目15 状态为all的产品信息
r($product->getProductsTest($projectIdList[5], $statusList[0])) && p()         && e('0');         // 测试获取不存在的项目状态为all的产品信息
r($product->getProductsTest($projectIdList[0], $statusList[1])) && p('1:name') && e('正常产品1'); // 测试获取项目11 状态为unclosed的产品信息
r($product->getProductsTest($projectIdList[1], $statusList[1])) && p('2:name') && e('正常产品2'); // 测试获取项目12 状态为unclosed的产品信息
r($product->getProductsTest($projectIdList[2], $statusList[1])) && p('3:name') && e('正常产品3'); // 测试获取项目13 状态为unclosed的产品信息
r($product->getProductsTest($projectIdList[3], $statusList[1])) && p('4:name') && e('正常产品4'); // 测试获取项目14 状态为unclosed的产品信息
r($product->getProductsTest($projectIdList[4], $statusList[1])) && p('5:name') && e('正常产品5'); // 测试获取项目15 状态为unclosed的产品信息
r($product->getProductsTest($projectIdList[5], $statusList[1])) && p()         && e('0');         // 测试获取不存在的项目状态为unclosed的产品信息
