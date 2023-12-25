#!/usr/bin/env php
<?php

/**

title=获取产品列表中每个产品的统计信息 productModel->getStats();
cid=0

- 获取第1个产品的名称第1条的name属性 @正常产品1
- 获取第1个产品的计划数第1条的plans属性 @0
- 获取第1个产品的发布数第1条的releases属性 @25
- 获取第1个产品的bug数第1条的totalBugs属性 @3
- 获取第1个产品的未解决bug数第1条的unresolvedBugs属性 @3
- 获取第1个产品的关闭bug数第1条的closedBugs属性 @0
- 获取第1个产品的已解决bug数第1条的fixedBugs属性 @0
- 获取第2个产品的名称第2条的name属性 @正常产品2
- 获取第2个产品的计划数第2条的plans属性 @0
- 获取第2个产品的发布数第2条的releases属性 @10
- 获取第2个产品的bug数第2条的totalBugs属性 @3
- 获取第2个产品的未解决bug数第2条的unresolvedBugs属性 @3
- 获取第2个产品的关闭bug数第2条的closedBugs属性 @0
- 获取第2个产品的已解决bug数第2条的fixedBugs属性 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('product')->gen(50);
zdTable('story')->gen(50);
zdTable('productplan')->gen(50);
zdTable('release')->gen(50);
zdTable('build')->gen(50);
zdTable('case')->gen(50);
zdTable('project')->gen(50);
zdTable('projectproduct')->gen(50);
zdTable('bug')->gen(50);
zdTable('doc')->gen(50);

$product = new productTest('admin');

$productIdList = array(1,2);
$list = $product->getStatsTest($productIdList);
r($list) && p('1:name')           && e('正常产品1'); // 获取第1个产品的名称
r($list) && p('1:plans')          && e('0');         // 获取第1个产品的计划数
r($list) && p('1:releases')       && e('25');        // 获取第1个产品的发布数
r($list) && p('1:totalBugs')      && e('3');         // 获取第1个产品的bug数
r($list) && p('1:unresolvedBugs') && e('3');         // 获取第1个产品的未解决bug数
r($list) && p('1:closedBugs')     && e('0');         // 获取第1个产品的关闭bug数
r($list) && p('1:fixedBugs')      && e('0');         // 获取第1个产品的已解决bug数

r($list) && p('2:name')           && e('正常产品2'); // 获取第2个产品的名称
r($list) && p('2:plans')          && e('0');         // 获取第2个产品的计划数
r($list) && p('2:releases')       && e('10');        // 获取第2个产品的发布数
r($list) && p('2:totalBugs')      && e('3');         // 获取第2个产品的bug数
r($list) && p('2:unresolvedBugs') && e('3');         // 获取第2个产品的未解决bug数
r($list) && p('2:closedBugs')     && e('0');         // 获取第2个产品的关闭bug数
r($list) && p('2:fixedBugs')      && e('0');         // 获取第2个产品的已解决bug数
