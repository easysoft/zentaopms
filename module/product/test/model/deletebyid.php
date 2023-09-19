#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';
su('admin');

zdTable('product')->gen(5);

/**

title=测试productModel->deleteByID();
timeout=0
cid=1

*/

$product = new productTest('admin');

r($product->deleteByIdTest(0))  && p()               && e('0');           // ID为空
r($product->deleteByIdTest(1))  && p('name,deleted') && e('正常产品1,1'); // 正常删除
r($product->deleteByIdTest(10)) && p()               && e('0');           // 不存在的ID
