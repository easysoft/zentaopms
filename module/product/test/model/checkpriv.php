#!/usr/bin/env php
<?php

/**

title=测试productModel->checkPriv();
cid=0

- 测试admin能否看到产品1 @1
- 测试admin能否看到产品2 @1
- 测试admin能否看到产品3 @1
- 测试admin能否看到产品4 @1
- 测试admin能否看到产品5 @1
- 测试admin能否看到不存在的产品 @1
- 测试po1能否看到产品1 @1
- 测试po1能否看到产品2 @1
- 测试po1能否看到产品3 @1
- 测试po1能否看到产品4 @1
- 测试po1能否看到产品5 @1
- 测试po1能否看到不存在的产品 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

$productIdList = array(1, 2, 3, 4, 5, 1000001);

$productTest = new productTest('admin');
$productTest->objectModel->app->user->admin = true;
r($productTest->checkPrivTest($productIdList[0])) && p() && e('1'); // 测试admin能否看到产品1
r($productTest->checkPrivTest($productIdList[1])) && p() && e('1'); // 测试admin能否看到产品2
r($productTest->checkPrivTest($productIdList[2])) && p() && e('1'); // 测试admin能否看到产品3
r($productTest->checkPrivTest($productIdList[3])) && p() && e('1'); // 测试admin能否看到产品4
r($productTest->checkPrivTest($productIdList[4])) && p() && e('1'); // 测试admin能否看到产品5
r($productTest->checkPrivTest($productIdList[5])) && p() && e('1'); // 测试admin能否看到不存在的产品

$productTest->objectModel->app->user->admin = false;
$productTest->objectModel->app->user->view->products = '1,2,3,4,5,6,7,8,9,20,21,48,49,50';
r($productTest->checkPrivTest($productIdList[0])) && p() && e('1'); // 测试po1能否看到产品1
r($productTest->checkPrivTest($productIdList[1])) && p() && e('1'); // 测试po1能否看到产品2
r($productTest->checkPrivTest($productIdList[2])) && p() && e('1'); // 测试po1能否看到产品3
r($productTest->checkPrivTest($productIdList[3])) && p() && e('1'); // 测试po1能否看到产品4
r($productTest->checkPrivTest($productIdList[4])) && p() && e('1'); // 测试po1能否看到产品5
r($productTest->checkPrivTest($productIdList[5])) && p() && e('0'); // 测试po1能否看到不存在的产品
