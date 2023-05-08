#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('product')->gen(10);

/**

title=测试 productModel->batchUpdate();
cid=1
pid=1

*/

$product = new productTest('admin');

$products = array();
$products[1] = new stdclass();
$products[1]->program = 1;
$products[1]->name    = '批量修改产品1';
$products[1]->type    = 'branch';
$products[1]->acl     = 'private';

$changes = $product->batchUpdateTest($products);
$changes = $changes[1];
r(array_shift($changes))   && p('field,old,new') && e('program,0,1');
r(array_shift($changes))   && p('field,old,new') && e('name,正常产品1,批量修改产品1');
r(array_shift($changes))   && p('field,old,new') && e('type,normal,branch');
r(array_shift($changes))   && p('field,old,new') && e('acl,open,private');

$products = array();
$products[2] = new stdclass();
$products[2]->program = 1;
$products[2]->name    = '批量修改产品1';
$products[2]->type    = 'branch';
$products[2]->acl     = 'private';

$changes = $product->batchUpdateTest($products);
$changes['message'] = str_replace('product#', 'product:', $changes['message']);
r($changes) && p('result,message') && e('fail,product:2『产品名称』已经有『批量修改产品1』这条记录了。\n'); //验证唯一。

r($product->batchUpdateTest(array())) && p() && e('0');   //不传任何数据。

$products = array();
$products[1] = new stdclass();
$products[1]->program = 1;
$products[1]->name    = '批量修改产品1';
$products[1]->type    = 'normal';
$products[1]->acl     = 'open';

$changes = $product->batchUpdateTest($products);
$changes = $changes[1];
r(array_shift($changes)) && p('field,old,new') && e('type,branch,normal');
r(array_shift($changes)) && p('field,old,new') && e('acl,private,open');
