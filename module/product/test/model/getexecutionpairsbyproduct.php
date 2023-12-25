#!/usr/bin/env php
<?php

/**

title=productModel->getExecutionPairsByProduct();
cid=0

- 不传入任何数据。 @0
- 只传入产品，不传入项目，检查敏捷项目。 @敏捷项目1/迭代5
- 只传入产品，不传入项目，检查不启用迭代的项目。 @敏捷项目1(不启用迭代的项目)
- 只传入产品，不传入项目，检查包含子阶段的项目。 @瀑布项目2/阶段10/阶段16
- 只传入产品，不传入项目，检查无子阶段的项目。 @瀑布项目3/阶段24
- 只传入产品，不传入项目，检查看板项目。 @看板项目4/看板28
- 只传入产品，不传入项目，是否包含关闭的迭代。 @1
- 只传入产品，不传入项目，是否包含不启用迭代的执行。 @1
- 传入产品，传入敏捷项目。 @5
- 传入产品，传入无关联数据的项目。 @0
- 传入产品，传入有子阶段的瀑布项目。 @6
- 传入产品，传入无子阶段的瀑布项目。 @6
- 传入产品，传入看板项目。 @4
- 传入无关联关系的产品。 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('project')->config('execution')->gen(32);
$projectProduct = zdTable('projectproduct');
$projectProduct->project->range('101-150');
$projectProduct->product->range('1');
$projectProduct->gen(28);

$productIDList = array(0, 1, 1000001);
$projectIDList = array(11, 21, 60, 61, 100);

$product = new productTest('admin');
$product->objectModel->app->user->view->sprints = implode(',', array_keys(array_fill('101', 28, 'test')));

r($product->getExecutionPairsByProductTest($productIDList[0])) && p() && e('0');  // 不传入任何数据。

$executions = $product->getExecutionPairsByProductTest($productIDList[1]);
r($executions[101]) && p() && e('敏捷项目1/迭代5');             // 只传入产品，不传入项目，检查敏捷项目。
r($executions[104]) && p() && e('敏捷项目1(不启用迭代的项目)'); // 只传入产品，不传入项目，检查不启用迭代的项目。
r($executions[112]) && p() && e('瀑布项目2/阶段10/阶段16');     // 只传入产品，不传入项目，检查包含子阶段的项目。
r($executions[120]) && p() && e('瀑布项目3/阶段24');            // 只传入产品，不传入项目，检查无子阶段的项目。
r($executions[124]) && p() && e('看板项目4/看板28');            // 只传入产品，不传入项目，检查看板项目。

$executions = $product->getExecutionPairsByProductTest($productIDList[1], 0, 'noclosed');
r(!isset($executions[101])) && p() && e('1'); // 只传入产品，不传入项目，是否包含关闭的迭代。

$executions = $product->getExecutionPairsByProductTest($productIDList[1], 0, 'multiple');
r(!isset($executions[104])) && p() && e('1'); // 只传入产品，不传入项目，是否包含不启用迭代的执行。

r(count($product->getExecutionPairsByProductTest($productIDList[1], $projectIDList[0]))) && p() && e('5'); // 传入产品，传入敏捷项目。
r(count($product->getExecutionPairsByProductTest($productIDList[1], $projectIDList[1]))) && p() && e('0'); // 传入产品，传入无关联数据的项目。
r(count($product->getExecutionPairsByProductTest($productIDList[1], $projectIDList[2]))) && p() && e('6'); // 传入产品，传入有子阶段的瀑布项目。
r(count($product->getExecutionPairsByProductTest($productIDList[1], $projectIDList[3]))) && p() && e('6'); // 传入产品，传入无子阶段的瀑布项目。
r(count($product->getExecutionPairsByProductTest($productIDList[1], $projectIDList[4]))) && p() && e('4'); // 传入产品，传入看板项目。

r($product->getExecutionPairsByProductTest($productIDList[2])) && p() && e('0'); // 传入无关联关系的产品。
