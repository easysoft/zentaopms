#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

function initData()
{
    zdTable('product')->config('product')->gen(100);
    zdTable('story')->config('story')->gen(100);
    zdTable('case')->config('case')->gen(100);
}
initData();

/**
title=productTao->getCaseCoveragePairs();
cid=2

 */

$tester = new productTest('admin');

$productList   = $tester->objectModel->getList();
$productIdList = array_keys($productList);
$pairs         = $tester->objectModel->getCaseCoveragePairs($productIdList);
//var_dump($pairs);

r($pairs[1])           && p('') && e('100');
r($pairs[7])           && p('') && e('25');
r($pairs[25])          && p('') && e('0');
r(isset($pairs[1000])) && p('') && e('0');
