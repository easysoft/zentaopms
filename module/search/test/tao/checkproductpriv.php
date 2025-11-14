#!/usr/bin/env php
<?php

/**

title=测试 searchTao::checkProductPriv();
timeout=0
cid=18319

- 执行searchTest模块的checkProductPrivTest方法，参数是$results1, $objectIdList1, $products1 第1条的objectID属性 @1
- 执行searchTest模块的checkProductPrivTest方法，参数是$results2, $objectIdList2, $products2  @0
- 执行searchTest模块的checkProductPrivTest方法，参数是$results3, $objectIdList3, $products3  @0
- 执行searchTest模块的checkProductPrivTest方法，参数是$results4, $objectIdList4, $products4  @0
- 执行searchTest模块的checkProductPrivTest方法，参数是$results5, $objectIdList5, $products5
 - 第1条的objectID属性 @1
 - 第3条的objectID属性 @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$product = zenData('product');
$product->id->range('1-10');
$product->name->range('Product1,Product2,Product3,Product4,Product5,Product6,Product7,Product8,Product9,Product10');
$product->code->range('code1,code2,code3,code4,code5,code6,code7,code8,code9,code10');
$product->shadow->range('0,0,0,0,0,0,0,1,1,1');
$product->type->range('normal');
$product->status->range('normal');
$product->acl->range('open');
$product->gen(10);

su('admin');

$searchTest = new searchTaoTest();

$results1 = array(1 => (object)array('id' => 1, 'objectID' => 1, 'objectType' => 'product', 'title' => 'Test Result 1'));
$objectIdList1 = array(1 => 1);
$products1 = '1,2,3,4,5,6,7';
r($searchTest->checkProductPrivTest($results1, $objectIdList1, $products1)) && p('1:objectID') && e('1');

$results2 = array(2 => (object)array('id' => 2, 'objectID' => 2, 'objectType' => 'product', 'title' => 'Test Result 2'));
$objectIdList2 = array(2 => 2);
$products2 = '1,3,4,5,6,7';
r(count($searchTest->checkProductPrivTest($results2, $objectIdList2, $products2))) && p() && e('0');

$results3 = array(4 => (object)array('id' => 4, 'objectID' => 8, 'objectType' => 'product', 'title' => 'Test Result 4'));
$objectIdList3 = array(8 => 4);
$products3 = '1,2,3,4,5,6,7,8,9,10';
r(count($searchTest->checkProductPrivTest($results3, $objectIdList3, $products3))) && p() && e('0');

$results4 = array();
$objectIdList4 = array();
$products4 = '1,2,3,4,5,6,7';
r(count($searchTest->checkProductPrivTest($results4, $objectIdList4, $products4))) && p() && e('0');

$results5 = array(1 => (object)array('id' => 1, 'objectID' => 1, 'objectType' => 'product', 'title' => 'Test Result 1'), 2 => (object)array('id' => 2, 'objectID' => 2, 'objectType' => 'product', 'title' => 'Test Result 2'), 3 => (object)array('id' => 3, 'objectID' => 3, 'objectType' => 'product', 'title' => 'Test Result 3'), 4 => (object)array('id' => 4, 'objectID' => 8, 'objectType' => 'product', 'title' => 'Test Result 4'), 5 => (object)array('id' => 5, 'objectID' => 9, 'objectType' => 'product', 'title' => 'Test Result 5'), 6 => (object)array('id' => 6, 'objectID' => 10, 'objectType' => 'product', 'title' => 'Test Result 6'));
$objectIdList5 = array(1 => 1, 2 => 2, 3 => 3, 8 => 4, 9 => 5, 10 => 6);
$products5 = '1,3,5,6,7';
r($searchTest->checkProductPrivTest($results5, $objectIdList5, $products5)) && p('1:objectID;3:objectID') && e('1;3');