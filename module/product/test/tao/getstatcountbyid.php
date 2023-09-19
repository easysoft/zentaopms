#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('productplan')->config('productplan')->gen(100);
zdTable('case')->config('case')->gen(100);
zdTable('bug')->config('bug')->gen(100);

/**
title=productTao->getStatCountByID();
timeout=0
cid=2

*/

$product = new productTest('admin');

r($product->objectModel->getStatCountByID(TABLE_PRODUCTPLAN, 1)) && p('') && e('5');
r($product->objectModel->getStatCountByID(TABLE_CASE, 1))        && p('') && e('4');
r($product->objectModel->getStatCountByID(TABLE_BUG, 1))         && p('') && e('3');
