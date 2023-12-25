#!/usr/bin/env php
<?php

/**

title=productModel->close();
cid=0

- 测试关闭产品1
 - 第0条的field属性 @status
 - 第0条的old属性 @normal
 - 第0条的new属性 @closed
- 测试关闭产品2
 - 第0条的field属性 @status
 - 第0条的old属性 @normal
 - 第0条的new属性 @closed
- 测试关闭产品3
 - 第0条的field属性 @status
 - 第0条的old属性 @normal
 - 第0条的new属性 @closed
- 测试关闭产品4
 - 第0条的field属性 @status
 - 第0条的old属性 @normal
 - 第0条的new属性 @closed
- 测试关闭产品5
 - 第0条的field属性 @status
 - 第0条的old属性 @normal
 - 第0条的new属性 @closed
- 测试关闭不存在产品 @0
- 测试关闭不存在产品 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('product')->gen(10);

$productIDList = array(1, 2, 3, 4, 5, 1000001, 0);

$product = new productTest('admin');

r($product->closeTest($productIDList[0])) && p('0:field,old,new') && e('status,normal,closed'); // 测试关闭产品1
r($product->closeTest($productIDList[1])) && p('0:field,old,new') && e('status,normal,closed'); // 测试关闭产品2
r($product->closeTest($productIDList[2])) && p('0:field,old,new') && e('status,normal,closed'); // 测试关闭产品3
r($product->closeTest($productIDList[3])) && p('0:field,old,new') && e('status,normal,closed'); // 测试关闭产品4
r($product->closeTest($productIDList[4])) && p('0:field,old,new') && e('status,normal,closed'); // 测试关闭产品5
r($product->closeTest($productIDList[5])) && p()                  && e('0');                    // 测试关闭不存在产品
r($product->closeTest($productIDList[6])) && p()                  && e('0');                    // 测试关闭不存在产品
