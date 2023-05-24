#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

$doclib = zdTable('doclib');
$doclib->addedBy->range('admin');
$doclib->addedDate->range('`' . date('Y-m-d H:i:s') . '`');
$doclib->gen(1);

/**

title=productModel->createmainlib();
cid=1
pid=1

*/

$product = new productTest('admin');

r($product->createMainLibTest('-1')) && p() && e('0');
r($product->createMainLibTest('0'))  && p() && e('0');
r($product->createMainLibTest('2'))  && p('product,name,type,main') && e('2,产品主库,product,1');
