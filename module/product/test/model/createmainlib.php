#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('doclib')->gen(0);

/**

title=productModel->createmainlib();
cid=1
pid=1

*/

$product = new productTest('admin');

r($product->createMainLibTest('-1')) && p() && e('0');
r($product->createMainLibTest('0'))  && p() && e('0');
r($product->createMainLibTest('1'))  && p('product,name,type,main') && e('1,产品主库,product,1');
