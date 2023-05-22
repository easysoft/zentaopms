#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('product')->config('product')->gen(5);
/**

title=productModel->getByIdList();
cid=1
pid=1

*/

$product = new productTest('admin');

$productIdList = array(1, 2, 3, 4, 5);

r($product->getByIdListTest($productIdList)) && p('1:name,program,shadow,line,type,status,PO,QD,acl,vision') && e('产品1,1,0,1,normal,normal,po1,qd1,open,rnd');    // 测试获取产品列表中产品1的信息
r($product->getByIdListTest($productIdList)) && p('2:name,program,shadow,line,type,status,PO,QD,acl,vision') && e('产品2,2,0,2,normal,normal,po1,qd1,open,rnd');    // 测试获取产品列表中产品2的信息
r($product->getByIdListTest($productIdList)) && p('3:name,program,shadow,line,type,status,PO,QD,acl,vision') && e('产品3,3,1,3,branch,closed,po2,qd2,private,rnd'); // 测试获取产品列表中产品3的信息
r($product->getByIdListTest($productIdList)) && p('4:name,program,shadow,line,type,status,PO,QD,acl,vision') && e('产品4,4,1,4,branch,closed,po2,qd2,private,rnd'); // 测试获取产品列表中产品4的信息
r($product->getByIdListTest($productIdList)) && p('5:name,program,shadow,line,type,status,PO,QD,acl,vision') && e('产品5,5,1,5,branch,closed,po3,qd3,private,rnd'); // 测试获取产品列表中产品5的信息
