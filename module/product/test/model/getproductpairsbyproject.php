#!/usr/bin/env php
<?php

/**

title=测试通过项目查询产品信息 productModel->getProductPairsByProject();
cid=0

- 测试获取项目11 状态为all的产品信息属性1 @正常产品1
- 测试获取项目12 状态为all的产品信息属性2 @正常产品2
- 测试获取项目13 状态为all的产品信息属性3 @正常产品3
- 测试获取项目14 状态为all的产品信息属性4 @正常产品4
- 测试获取项目15 状态为all的产品信息属性5 @正常产品5
- 测试获取不存在的项目状态为all的产品信息 @0
- 测试获取项目11 状态为unclosed的产品信息属性1 @正常产品1
- 测试获取项目12 状态为unclosed的产品信息属性2 @正常产品2
- 测试获取项目13 状态为unclosed的产品信息属性3 @正常产品3
- 测试获取项目14 状态为unclosed的产品信息属性4 @正常产品4
- 测试获取项目15 状态为unclosed的产品信息属性5 @正常产品5
- 测试获取不存在的项目状态为unclosed的产品信息 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('project')->gen(50);
zdTable('user')->gen(5);
zdTable('projectproduct')->gen(50);

$projectIDList = array('11', '12', '13', '14', '15', '1000001');
$statusList    = array('all', 'noclosed');

$product = new productTest('admin');
r($product->getProductPairsByProjectTest($projectIDList[0], $statusList[0])) && p('1') && e('正常产品1'); // 测试获取项目11 状态为all的产品信息
r($product->getProductPairsByProjectTest($projectIDList[1], $statusList[0])) && p('2') && e('正常产品2'); // 测试获取项目12 状态为all的产品信息
r($product->getProductPairsByProjectTest($projectIDList[2], $statusList[0])) && p('3') && e('正常产品3'); // 测试获取项目13 状态为all的产品信息
r($product->getProductPairsByProjectTest($projectIDList[3], $statusList[0])) && p('4') && e('正常产品4'); // 测试获取项目14 状态为all的产品信息
r($product->getProductPairsByProjectTest($projectIDList[4], $statusList[0])) && p('5') && e('正常产品5'); // 测试获取项目15 状态为all的产品信息
r($product->getProductPairsByProjectTest($projectIDList[5], $statusList[0])) && p()    && e('0');         // 测试获取不存在的项目状态为all的产品信息
r($product->getProductPairsByProjectTest($projectIDList[0], $statusList[1])) && p('1') && e('正常产品1'); // 测试获取项目11 状态为unclosed的产品信息
r($product->getProductPairsByProjectTest($projectIDList[1], $statusList[1])) && p('2') && e('正常产品2'); // 测试获取项目12 状态为unclosed的产品信息
r($product->getProductPairsByProjectTest($projectIDList[2], $statusList[1])) && p('3') && e('正常产品3'); // 测试获取项目13 状态为unclosed的产品信息
r($product->getProductPairsByProjectTest($projectIDList[3], $statusList[1])) && p('4') && e('正常产品4'); // 测试获取项目14 状态为unclosed的产品信息
r($product->getProductPairsByProjectTest($projectIDList[4], $statusList[1])) && p('5') && e('正常产品5'); // 测试获取项目15 状态为unclosed的产品信息
r($product->getProductPairsByProjectTest($projectIDList[5], $statusList[1])) && p()    && e('0');         // 测试获取不存在的项目状态为unclosed的产品信息
