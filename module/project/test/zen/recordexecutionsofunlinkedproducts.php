#!/usr/bin/env php
<?php

/**

title=测试 projectZen::recordExecutionsOfUnlinkedProducts();
timeout=0
cid=0

- 执行projectTest模块的recordExecutionsOfUnlinkedProductsTest方法，参数是array  @0
- 执行projectTest模块的recordExecutionsOfUnlinkedProductsTest方法，参数是array 第101条的action属性 @unlinkproduct
- 执行projectTest模块的recordExecutionsOfUnlinkedProductsTest方法，参数是array
 - 第101条的objectID属性 @101
 - 第103条的objectID属性 @103
- 执行projectTest模块的recordExecutionsOfUnlinkedProductsTest方法，参数是array  @0
- 执行projectTest模块的recordExecutionsOfUnlinkedProductsTest方法，参数是array
 - 第101条的action属性 @unlinkproduct
 - 第103条的action属性 @unlinkproduct

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('project')->gen(30);

$product = zenData('product');
$product->id->range('1-10');
$product->name->range('ProductA,ProductB,ProductC,ProductD,ProductE,ProductF,ProductG,ProductH,ProductI,ProductJ');
$product->gen(10);

$projectproduct = zenData('projectproduct');
$projectproduct->project->range('101,101,102,102,103,103,103');
$projectproduct->product->range('1,2,3,4,1,5,6');
$projectproduct->gen(7);

su('admin');

$projectTest = new projectZenTest();

// 准备测试数据
$product1 = new stdclass();
$product1->id = 1;
$product1->name = 'ProductA';

$product2 = new stdclass();
$product2->id = 2;
$product2->name = 'ProductB';

$product3 = new stdclass();
$product3->id = 3;
$product3->name = 'ProductC';

$product4 = new stdclass();
$product4->id = 4;
$product4->name = 'ProductD';

$product5 = new stdclass();
$product5->id = 5;
$product5->name = 'ProductE';

$product7 = new stdclass();
$product7->id = 7;
$product7->name = 'ProductG';

// 测试步骤1:无取消关联产品时不创建action记录
r(count($projectTest->recordExecutionsOfUnlinkedProductsTest(array(1 => $product1, 2 => $product2), array(1, 2), array(101, 102)))) && p() && e('0');
// 测试步骤2:取消一个产品关联且该产品在一个执行中
r($projectTest->recordExecutionsOfUnlinkedProductsTest(array(1 => $product1, 2 => $product2), array(2), array(101, 102))) && p('101:action') && e('unlinkproduct');
// 测试步骤3:取消多个执行中的产品关联
r($projectTest->recordExecutionsOfUnlinkedProductsTest(array(1 => $product1, 2 => $product2, 3 => $product3), array(2, 3), array(101, 102, 103))) && p('101:objectID;103:objectID') && e('101;103');
// 测试步骤4:取消产品关联但执行未关联该产品
r(count($projectTest->recordExecutionsOfUnlinkedProductsTest(array(7 => $product7), array(), array(101, 102, 103)))) && p() && e('0');
// 测试步骤5:取消产品关联且多个执行都关联了该产品
r($projectTest->recordExecutionsOfUnlinkedProductsTest(array(1 => $product1, 3 => $product3), array(3), array(101, 102, 103))) && p('101:action;103:action') && e('unlinkproduct;unlinkproduct');