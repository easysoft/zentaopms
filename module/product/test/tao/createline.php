#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('module')->gen(1);

/**

title=productModel->createLine();
cid=1
pid=1

*/

$product = new productTest('admin');

r($product->createLineTest('1', 'test line1')) && p('root,name') && e('1,test line1');
r($product->createLineTest('0', 'test line1')) && p() && e('0');
r($product->createLineTest('1', '')) && p() && e('0');
r($product->createLineTest('1', "<script>alert('test')</script>")) && p('root,name') && e('1,&lt;script&gt;alert(&#039;test&#039;)&lt;/script&gt;');
