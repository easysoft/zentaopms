#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/product.class.php';

/**

title=productModel->close();
cid=1
pid=1

测试关闭产品1 >> status,normal,closed
测试关闭产品2 >> status,normal,closed
测试关闭产品3 >> status,normal,closed
测试关闭产品4 >> status,normal,closed
测试关闭产品5 >> status,normal,closed
测试关闭不存在产品 >> 0

*/

$productIDList = array('1', '2', '3', '4', '5', '1000001');

$product = new productTest('admin');

r($product->closeTest($productIDList[0])) && p('0:field,old,new') && e('status,normal,closed'); // 测试关闭产品1
r($product->closeTest($productIDList[1])) && p('0:field,old,new') && e('status,normal,closed'); // 测试关闭产品2
r($product->closeTest($productIDList[2])) && p('0:field,old,new') && e('status,normal,closed'); // 测试关闭产品3
r($product->closeTest($productIDList[3])) && p('0:field,old,new') && e('status,normal,closed'); // 测试关闭产品4
r($product->closeTest($productIDList[4])) && p('0:field,old,new') && e('status,normal,closed'); // 测试关闭产品5
r($product->closeTest($productIDList[5])) && p()                  && e('0');                    // 测试关闭不存在产品
