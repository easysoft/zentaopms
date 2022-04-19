#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/product.class.php';

/**

title=测试productModel->getPairs();
cid=1
pid=1

测试项目集10下的11号产品 >> 正常产品11
测试项目集10下的55号产品 >> 多分支产品55
测试项目集10下的99号产品 >> 多平台产品99
测试不存在的项目集 >> 没有数据
返回所有产品的数量 >> 120
返回项目集10下的所有产品 >> 10
测试项目集10下的未关闭产品5 >> 正常产品6
返回项目集10下的未关闭产品的数量 >> 6

*/

$product = new productTest('admin');

$t_peoduct10 = array('programID'=>'10');
$t_noproduct = array('programID'=>'11');
$t_alproduct = array('programID'=>'0');
$t_notclose5 = array('programID'=>'5');

r($product->getProductPairs($t_peoduct10['programID']))       && p('11') && e('正常产品11');   // 测试项目集10下的11号产品
r($product->getProductPairs($t_peoduct10['programID']))       && p('55') && e('多分支产品55'); // 测试项目集10下的55号产品
r($product->getProductPairs($t_peoduct10['programID']))       && p('99') && e('多平台产品99'); // 测试项目集10下的99号产品
r($product->getProductPairs($t_noproduct['programID']))       && p()     && e('没有数据');     // 测试不存在的项目集
r($product->getProductPairsCount($t_alproduct['programID']))  && p()     && e('120');          // 返回所有产品的数量
r($product->getProductPairsCount($t_peoduct10['programID']))  && p()     && e('10');           // 返回项目集10下的所有产品
r($product->getNoclosedPairs($t_notclose5['programID']))      && p('6')  && e('正常产品6');    // 测试项目集10下的未关闭产品5
r($product->getNoclosedPairsCount($t_peoduct10['programID'])) && p()     && e('6');            // 返回项目集10下的未关闭产品的数量