#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

/**
title=测试通过项目id查询关联的产品id productModel->getProductIDByProject();
timeout=0
cid=1

*/

zdTable('project')->gen(20);
zdTable('product')->gen(20);
zdTable('projectproduct')->config('projectproduct')->gen(50);

$projectIDList = array(11, 0, 1000001);

$product = new productTest('admin');

r($product->getProductIDByProjectTest($projectIDList[0])) && p() && e('1'); // 正常关联查询
r($product->getProductIDByProjectTest($projectIDList[1])) && p() && e('1'); // 空的项目ID
r($product->getProductIDByProjectTest($projectIDList[2])) && p() && e('0'); // 不存在的项目ID
