#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('product')->gen(10);

/**

title=测试productModel->fetchPairs();
cid=1
pid=1

*/

$product = new productTest('admin');

$productID = 1;
$programID = 0;
$data      = new stdclass();
$data->program = 1;
$data->name    = '修改产品1';
$data->code    = 'newcode1';
$data->type    = 'branch';
$data->acl     = 'private';

r($product->doUpdateTest($data, $productID, $programID)) && p('program,name,code,type,acl') && e('1,修改产品1,newcode1,branch,private');

$data->name = '修改产品2';
$data->code = 'newcode2';
r($product->doUpdateTest($data, 0, 1))   && p() && e('0');
r($product->doUpdateTest($data, 0, 0))   && p() && e('0');
r($product->doUpdateTest($data, 0, -1))  && p() && e('0');
r($product->doUpdateTest($data, -1, 1))  && p() && e('0');
r($product->doUpdateTest($data, -1, 0))  && p() && e('0');
r($product->doUpdateTest($data, -1, -1)) && p() && e('0');
r($product->doUpdateTest(new stdclass(), 3, 2)) && p('name,code') && e('正常产品3,code3');

$productID = 2;
$programID = 1;
$data      = new stdclass();
$data->program = 1;
$data->name    = '修改产品1';
$data->code    = 'newcode1';
$data->type    = 'branch';
$data->acl     = 'private';
$result = $product->doUpdateTest($data, $productID, $programID);

r(array_shift($result['name'])) && p() && e('『产品名称』已经有『修改产品1』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。');
r(array_shift($result['code'])) && p() && e('『产品代号』已经有『newcode1』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。');

