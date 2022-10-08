#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/product.class.php';

/**

title=productModel->getProjectStatsByProduct();
cid=1
pid=1

测试获取产品1的 browseType为all的信息 >> 项目1;项目11
测试获取产品1的 browseType为unclosed的信息 >> 0
测试获取产品1的 browseType为unplan的信息 >> 0
测试获取产品1的 browseType为allstory的信息 >> 0
测试获取产品2的 browseType为all的信息 >> 项目2;项目12
测试获取产品2的 browseType为assignedtome的信息 >> 0
测试获取产品2的 browseType为openedbyme的信息 >> 0
测试获取产品2的 browseType为reviewedbyme的信息 >> 0
测试获取产品3的 browseType为all的信息 >> 项目3;项目13
测试获取产品3的 browseType为reviewbyme的信息 >> 0
测试获取产品3的 browseType为closedbyme的信息 >> 0
测试获取产品3的 browseType为draftstory的信息 >> 0
测试获取产品4的 browseType为all的信息 >> 项目4;项目14
测试获取产品4的 browseType为activestory的信息 >> 0
测试获取产品4的 browseType为changedstory的信息 >> 0
测试获取产品5的 browseType为all的信息 >> 项目5;项目15
测试获取产品5的 browseType为willclose的信息 >> 0
测试获取产品5的 browseType为closedstory的信息 >> 0
测试获取不存在产品的信息 >> 0

*/

$productIDList = array('1', '2', '3', '4', '5', '1000001');
$browseType    = array('unclosed', 'unplan', 'allstory', 'assignedtome', 'openedbyme', 'reviewedbyme', 'reviewbyme', 'closedbyme', 'draftstory', 'activestory', 'changedstory', 'willclose', 'closedstory');

$product = new productTest('admin');

r($product->getProjectStatsByProductTest($productIDList[0]))                  && p('11;21') && e('项目1;项目11');   // 测试获取产品1的 browseType为all的信息
r($product->getProjectStatsByProductTest($productIDList[0], $browseType[0]))  && p()        && e('0');              // 测试获取产品1的 browseType为unclosed的信息
r($product->getProjectStatsByProductTest($productIDList[0], $browseType[1]))  && p()        && e('0');              // 测试获取产品1的 browseType为unplan的信息
r($product->getProjectStatsByProductTest($productIDList[0], $browseType[2]))  && p()        && e('0');              // 测试获取产品1的 browseType为allstory的信息
r($product->getProjectStatsByProductTest($productIDList[1]))                  && p('12;22') && e('项目2;项目12');   // 测试获取产品2的 browseType为all的信息
r($product->getProjectStatsByProductTest($productIDList[1], $browseType[3]))  && p()        && e('0');              // 测试获取产品2的 browseType为assignedtome的信息
r($product->getProjectStatsByProductTest($productIDList[1], $browseType[4]))  && p()        && e('0');              // 测试获取产品2的 browseType为openedbyme的信息
r($product->getProjectStatsByProductTest($productIDList[1], $browseType[5]))  && p()        && e('0');              // 测试获取产品2的 browseType为reviewedbyme的信息
r($product->getProjectStatsByProductTest($productIDList[2]))                  && p('13;23') && e('项目3;项目13');   // 测试获取产品3的 browseType为all的信息
r($product->getProjectStatsByProductTest($productIDList[2], $browseType[6]))  && p()        && e('0');              // 测试获取产品3的 browseType为reviewbyme的信息
r($product->getProjectStatsByProductTest($productIDList[2], $browseType[7]))  && p()        && e('0');              // 测试获取产品3的 browseType为closedbyme的信息
r($product->getProjectStatsByProductTest($productIDList[2], $browseType[8]))  && p()        && e('0');              // 测试获取产品3的 browseType为draftstory的信息
r($product->getProjectStatsByProductTest($productIDList[3]))                  && p('14;24') && e('项目4;项目14');   // 测试获取产品4的 browseType为all的信息
r($product->getProjectStatsByProductTest($productIDList[3], $browseType[9]))  && p()        && e('0');              // 测试获取产品4的 browseType为activestory的信息
r($product->getProjectStatsByProductTest($productIDList[3], $browseType[10])) && p()        && e('0');              // 测试获取产品4的 browseType为changedstory的信息
r($product->getProjectStatsByProductTest($productIDList[4]))                  && p('15;25') && e('项目5;项目15');   // 测试获取产品5的 browseType为all的信息
r($product->getProjectStatsByProductTest($productIDList[4], $browseType[11])) && p()        && e('0');              // 测试获取产品5的 browseType为willclose的信息
r($product->getProjectStatsByProductTest($productIDList[4], $browseType[12])) && p()        && e('0');              // 测试获取产品5的 browseType为closedstory的信息
r($product->getProjectStatsByProductTest($productIDList[5]))                  && p()        && e('0');              // 测试获取不存在产品的信息