#!/usr/bin/env php
<?php

/**

title=productModel->summary();
timeout=0
cid=17528

- 获取正确的产品的需求统计 @本页共 <strong>2</strong> 个研发需求，预计 <strong>10</strong> 个工时，用例覆盖率 <strong>50%%</strong>。
- 获取正确的产品的需求统计 @本页共 <strong>0</strong> 个用户需求，预计 <strong>0</strong> 个工时。
- 获取正确的产品的需求统计 @本页共 <strong>0</strong> 个研发需求，预计 <strong>0</strong> 个工时，用例覆盖率 <strong>0%%</strong>。
- 获取空的产品的需求统计 @本页共 <strong>0</strong> 个研发需求，预计 <strong>0</strong> 个工时，用例覆盖率 <strong>0%%</strong>。
- 获取空的产品的需求统计 @本页共 <strong>0</strong> 个用户需求，预计 <strong>0</strong> 个工时。
- 获取空的产品的需求统计 @本页共 <strong>0</strong> 个研发需求，预计 <strong>0</strong> 个工时，用例覆盖率 <strong>0%%</strong>。
- 获取错误的产品的需求统计 @本页共 <strong>0</strong> 个研发需求，预计 <strong>0</strong> 个工时，用例覆盖率 <strong>0%%</strong>。
- 获取错误的产品的需求统计 @本页共 <strong>0</strong> 个用户需求，预计 <strong>0</strong> 个工时。
- 获取错误的产品的需求统计 @本页共 <strong>0</strong> 个研发需求，预计 <strong>0</strong> 个工时，用例覆盖率 <strong>0%%</strong>。

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$story = zenData('story');
$story->estimate->range('1-10:2');
$story->gen(100);

zenData('case')->gen(2);

$productIDList = array(1, 0, 10000);
$typeList      = array('story', 'requirement', 'error');

global $app;
$app->rawModule  = 'product';
$app->rawMethod  = 'browse';
$app->moduleName = 'product';

$product = new productTest('admin');
$product->objectModel->lang->SRCommon = '研发需求';
$product->objectModel->lang->URCommon = '用户需求';
include($product->objectModel->app->getBasePath() . 'module/product/lang/zh-cn.php');

r($product->summaryTest($productIDList[0], $typeList[0]))  && p() && e('本页共 <strong>2</strong> 个研发需求，预计 <strong>10</strong> 个工时，用例覆盖率 <strong>50%%</strong>。');  // 获取正确的产品的需求统计
r($product->summaryTest($productIDList[0], $typeList[1]))  && p() && e('本页共 <strong>1</strong> 个用户需求，预计 <strong>1</strong> 个工时。');                                     // 获取正确的产品的需求统计
r($product->summaryTest($productIDList[0], $typeList[2]))  && p() && e('本页共 <strong>0</strong> 个研发需求，预计 <strong>0</strong> 个工时，用例覆盖率 <strong>0%%</strong>。');     // 获取正确的产品的需求统计

r($product->summaryTest($productIDList[1], $typeList[0]))  && p() && e('本页共 <strong>0</strong> 个研发需求，预计 <strong>0</strong> 个工时，用例覆盖率 <strong>0%%</strong>。'); // 获取空的产品的需求统计
r($product->summaryTest($productIDList[1], $typeList[1]))  && p() && e('本页共 <strong>0</strong> 个用户需求，预计 <strong>0</strong> 个工时。');                                  // 获取空的产品的需求统计
r($product->summaryTest($productIDList[1], $typeList[2]))  && p() && e('本页共 <strong>0</strong> 个研发需求，预计 <strong>0</strong> 个工时，用例覆盖率 <strong>0%%</strong>。');  // 获取空的产品的需求统计

r($product->summaryTest($productIDList[2], $typeList[0]))  && p() && e('本页共 <strong>0</strong> 个研发需求，预计 <strong>0</strong> 个工时，用例覆盖率 <strong>0%%</strong>。'); // 获取错误的产品的需求统计
r($product->summaryTest($productIDList[2], $typeList[1]))  && p() && e('本页共 <strong>0</strong> 个用户需求，预计 <strong>0</strong> 个工时。');                                  // 获取错误的产品的需求统计
r($product->summaryTest($productIDList[2], $typeList[2]))  && p() && e('本页共 <strong>0</strong> 个研发需求，预计 <strong>0</strong> 个工时，用例覆盖率 <strong>0%%</strong>。');  // 获取错误的产品的需求统计
