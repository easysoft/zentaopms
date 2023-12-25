#!/usr/bin/env php
<?php

/**

title=productTao->getExecutionCountPairs();
cid=0

- 执行$pairs[3] @1
- 执行$pairs[10000] @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

function initData()
{
    zdTable('projectproduct')->config('projectproduct')->gen(100);
    zdTable('project')->config('project')->gen(100);
}
initData();

$product = new productTest('admin');
$pairs = $product->objectModel->getExecutionCountPairs(array(3,4,100,10000));

r($pairs[3])            && p('') && e('1');
r(isset($pairs[10000])) && p('') && e('0');
