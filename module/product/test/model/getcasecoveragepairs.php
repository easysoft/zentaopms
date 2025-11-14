#!/usr/bin/env php
<?php

/**

title=productModel->getCaseCoveragePairs();
timeout=0
cid=17488

- 获取产品ID为0的用例覆盖率 @0
- 获取产品ID为1的用例覆盖率属性1 @20
- 获取产品ID为2的用例覆盖率属性2 @20
- 获取产品ID为10的用例覆盖率属性10 @20
- 获取产品ID不存在的用例覆盖率 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

zenData('product')->loadYaml('product')->gen(100);
zenData('story')->loadYaml('story')->gen(100);
zenData('case')->gen(100);
zenData('user')->gen(5);
su('admin');

$tester = new productTest('admin');

$productList   = $tester->objectModel->getList();
$productIdList[0] = array(0);
$productIdList[1] = array_keys($productList);
$productIdList[2] = array(1000);

$emptyDataResult    = $tester->objectModel->getCaseCoveragePairs($productIdList[0]);
$normalDataResult   = $tester->objectModel->getCaseCoveragePairs($productIdList[1]);
$notExistDataResult = $tester->objectModel->getCaseCoveragePairs($productIdList[2]);

r($emptyDataResult)    && p('')   && e('0');  // 获取产品ID为0的用例覆盖率
r($normalDataResult)   && p('1')  && e('20'); // 获取产品ID为1的用例覆盖率
r($normalDataResult)   && p('2')  && e('20'); // 获取产品ID为2的用例覆盖率
r($normalDataResult)   && p('10') && e('20'); // 获取产品ID为10的用例覆盖率
r($notExistDataResult) && p('')   && e('0');  // 获取产品ID不存在的用例覆盖率