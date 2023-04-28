#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('product')->gen(50);

/**

title=测试productModel->getPairs();
cid=1
pid=1

*/

$product = new productTest('admin');

r($product->getProductPairs())       && p('11') && e('正常产品11');   // 测试项目集10下的11号产品
r($product->getProductPairs('all'))       && p('55') && e('多分支产品55'); // 测试项目集10下的55号产品
r($product->getProductPairs('noclosed'))       && p('99') && e('多平台产品99'); // 测试项目集10下的99号产品
r($product->getProductPairs('all', 1))       && p()     && e('没有数据');     // 测试不存在的项目集
r($product->getProductPairs('all', -1))       && p()     && e('没有数据');     // 测试不存在的项目集
r($product->getProductPairs('all', 10001, '1'))       && p()     && e('没有数据');     // 测试不存在的项目集
r($product->getProductPairs('noclosed', 1, '22,23'))       && p()     && e('没有数据');     // 测试不存在的项目集
r($product->getProductPairs('noclosed', 1, '22,23', 'all'))       && p()     && e('没有数据');     // 测试不存在的项目集
r($product->getProductPairs('noclosed', 1, '22,23', '1'))       && p()     && e('没有数据');     // 测试不存在的项目集
