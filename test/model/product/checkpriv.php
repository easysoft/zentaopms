#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/product.class.php';

#su('admin');

/**

title=测试productModel->checkPriv();
cid=1
pid=1

测试admin能否看到产品1 >> 1
测试admin能否看到产品2 >> 1
测试admin能否看到产品3 >> 1
测试admin能否看到产品4 >> 1
测试admin能否看到产品5 >> 1
测试admin能否看到不存在的产品 >> 1
测试po1能否看到产品1 >> 1
测试po1能否看到产品2 >> 1
测试po1能否看到产品3 >> 1
测试po1能否看到产品4 >> 1
测试po1能否看到产品5 >> 1
测试po1能否看到不存在的产品 >> 2

*/

$productIDList = array('1', '2', '3', '4', '5', '1000001');

$productTest = new productTest('admin');
r($productTest->checkPrivTest($productIDList[0])) && p() && e('1'); // 测试admin能否看到产品1
r($productTest->checkPrivTest($productIDList[1])) && p() && e('1'); // 测试admin能否看到产品2
r($productTest->checkPrivTest($productIDList[2])) && p() && e('1'); // 测试admin能否看到产品3
r($productTest->checkPrivTest($productIDList[3])) && p() && e('1'); // 测试admin能否看到产品4
r($productTest->checkPrivTest($productIDList[4])) && p() && e('1'); // 测试admin能否看到产品5
r($productTest->checkPrivTest($productIDList[5])) && p() && e('1'); // 测试admin能否看到不存在的产品

$productTest = new productTest('po1');
r($productTest->checkPrivTest($productIDList[0])) && p() && e('1'); // 测试po1能否看到产品1
r($productTest->checkPrivTest($productIDList[1])) && p() && e('1'); // 测试po1能否看到产品2
r($productTest->checkPrivTest($productIDList[2])) && p() && e('1'); // 测试po1能否看到产品3
r($productTest->checkPrivTest($productIDList[3])) && p() && e('1'); // 测试po1能否看到产品4
r($productTest->checkPrivTest($productIDList[4])) && p() && e('1'); // 测试po1能否看到产品5
r($productTest->checkPrivTest($productIDList[5])) && p() && e('2'); // 测试po1能否看到不存在的产品

