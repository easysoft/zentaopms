#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('product')->gen(50);

/**

title=测试productModel->fetchPairs();
cid=1
pid=1

*/

$product = new productTest('admin');
$product->objectModel->app->user->admin = true;

r(count($product->fetchPairsTest()))                             && p() && e('40');
r(count($product->fetchPairsTest('all')))                        && p() && e('45');
r(count($product->fetchPairsTest('noclosed')))                   && p() && e('22');
r(count($product->fetchPairsTest('all', 1)))                     && p() && e('5');
r(count($product->fetchPairsTest('all', -1)))                    && p() && e('0');
r(count($product->fetchPairsTest('all', 10001, '1')))            && p() && e('1');
r(count($product->fetchPairsTest('', 1, '23,24')))               && p() && e('5');
r(count($product->fetchPairsTest('noclosed', 1, '23,24')))       && p() && e('4');
r(count($product->fetchPairsTest('noclosed', 1, '23,24', 'all')))&& p() && e('4');
r(count($product->fetchPairsTest('noclosed', 1, '23,24', '1')))  && p() && e('0');
r(count($product->fetchPairsTest('noclosed', 1, '23,24', '2')))  && p() && e('0');

$product->objectModel->app->user->admin = false;
$product->objectModel->app->user->view->products = '1,2,3,4,5,6,7,8,9,20,21,48,49,50';
r(count($product->fetchPairsTest()))                             && p() && e('9');
r(count($product->fetchPairsTest('all')))                        && p() && e('12');
r(count($product->fetchPairsTest('noclosed')))                   && p() && e('9');
r(count($product->fetchPairsTest('all', 1)))                     && p() && e('1');
r(count($product->fetchPairsTest('all', -1)))                    && p() && e('0');
r(count($product->fetchPairsTest('all', 10001, '1')))            && p() && e('1');
r(count($product->fetchPairsTest('', 1, '23,24')))               && p() && e('3');
r(count($product->fetchPairsTest('noclosed', 1, '23,24')))       && p() && e('3');
r(count($product->fetchPairsTest('noclosed', 1, '23,24', 'all')))&& p() && e('3');
r(count($product->fetchPairsTest('noclosed', 1, '23,24', '1')))  && p() && e('0');
r(count($product->fetchPairsTest('noclosed', 1, '23,24', '2')))  && p() && e('0');
