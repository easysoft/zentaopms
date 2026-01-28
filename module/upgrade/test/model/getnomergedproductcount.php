#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->getNoMergedProductCount();
timeout=0
cid=19525

- 获取没有项目集的产品数量 @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$product = zenData('product');
$product->program->range('0,1');
$product->gen(10);

$upgrade = new upgradeModelTest();
r($upgrade->getNoMergedProductCountTest()) && p() && e('5');  //获取没有项目集的产品数量
