#!/usr/bin/env php
<?php

/**

title=productTao->syncProgramToProduct();
timeout=0
cid=17560

- 测试项目集ID和产品线所属项目集是同一个的情况属性program @1
- 测试项目集ID和产品线所属项目集不是同一个的情况属性program @2
- 测试项目集ID和产品线所属项目集不是同一个的情况属性program @1
- 测试项目集ID和产品线所属项目集不是同一个的情况属性program @2
- 测试项目集ID和产品线所属项目集不是同一个的情况属性action @changedprogram

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$products = zenData('product')->loadYaml('product');
$products->program->range('1-3');
$products->gen(30);

$product = new productTest('admin');

$programIdList = array(1, 2, 3);
$lineIdList    = array(1, 2, 3);

r($product->syncProgramToProductTest($programIdList[0], $lineIdList[0]))           && p('program') && e('1');              // 测试项目集ID和产品线所属项目集是同一个的情况
r($product->syncProgramToProductTest($programIdList[1], $lineIdList[0]))           && p('program') && e('2');              // 测试项目集ID和产品线所属项目集不是同一个的情况
r($product->syncProgramToProductTest($programIdList[0], $lineIdList[1]))           && p('program') && e('1');              // 测试项目集ID和产品线所属项目集不是同一个的情况
r($product->syncProgramToProductTest($programIdList[1], $lineIdList[2]))           && p('program') && e('2');              // 测试项目集ID和产品线所属项目集不是同一个的情况
r($product->syncProgramToProductTest($programIdList[2], $lineIdList[1], 'action')) && p('action')  && e('changedprogram'); // 测试项目集ID和产品线所属项目集不是同一个的情况
