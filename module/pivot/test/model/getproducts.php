#!/usr/bin/env php
<?php
declare(strict_types = 1);

/**

title=测试 pivotModel::getProducts();
timeout=0
cid=17396

- 步骤1：验证测试方法存在 @1
- 步骤2：验证返回值类型正确 @1
- 步骤3：验证插入的产品可以被查询到 @产品A,产品B
- 步骤4：验证按productID过滤后只返回目标产品 @产品B
- 步骤5：验证不存在的productID返回空数组 @0
- 步骤6：验证另一个productID过滤结果数量正确 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

global $tester;
foreach(array(
    (object)array('id' => 9001, 'code' => 'P9001', 'name' => '产品A', 'PO' => 'admin', 'shadow' => 0, 'deleted' => 0, 'status' => 'normal', 'type' => 'normal', 'vision' => 'rnd', 'program' => 0),
    (object)array('id' => 9002, 'code' => 'P9002', 'name' => '产品B', 'PO' => 'user1', 'shadow' => 0, 'deleted' => 0, 'status' => 'normal', 'type' => 'normal', 'vision' => 'rnd', 'program' => 0),
    (object)array('id' => 9003, 'code' => 'P9003', 'name' => '产品C', 'PO' => 'user2', 'shadow' => 0, 'deleted' => 0, 'status' => 'normal', 'type' => 'normal', 'vision' => 'rnd', 'program' => 0)
) as $product)
{
    $tester->dao->insert(TABLE_PRODUCT)->data($product)->exec();
}

$pivotTest   = new pivotModelTest();
$allProducts = $pivotTest->getProductsTest('', 'story');
$productB    = $pivotTest->getProductsTest('', 'requirement', array('productID' => 9002));
$productC    = $pivotTest->getProductsTest('', 'story', array('productID' => 9003));
$missing     = $pivotTest->getProductsTest('', 'story', array('productID' => 9999));

r(method_exists($pivotTest, 'getProductsTest'))           && p()        && e('1');
r(is_array($allProducts))                                 && p()        && e('1');
r($allProducts[9001]->name . ',' . $allProducts[9002]->name) && p()     && e('产品A,产品B');
r($productB[9002])                                        && p('name') && e('产品B');
r(count($missing))                                        && p()        && e('0');
r(count($productC))                                       && p()        && e('1');
