#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('product')->gen(50);

/**

title=测试productModel->getPairs();
timeout=0
cid=1

- 执行product模块的getProductPairs方法 @40

- 执行product模块的getProductPairs方法，参数是all @45

- 执行product模块的getProductPairs方法，参数是noclosed @22

- 执行product模块的getProductPairs方法，参数是all', 1 @5

- 执行product模块的getProductPairs方法，参数是all', -1 @0

- 执行product模块的getProductPairs方法，参数是all', 10001, 1 @1

- 执行product模块的getProductPairs方法，参数是, 1, 23,24 @5

- 执行product模块的getProductPairs方法，参数是noclosed', 1, 23,24 @4

- 执行product模块的getProductPairs方法，参数是noclosed', 1, 23,24', all @4

- 执行product模块的getProductPairs方法，参数是noclosed', 1, 23,24', 1 @0

- 执行product模块的getProductPairs方法，参数是noclosed', 1, 23,24', 2 @0

- 执行product模块的getProductPairs方法 @9

- 执行product模块的getProductPairs方法，参数是all @12

- 执行product模块的getProductPairs方法，参数是noclosed @9

- 执行product模块的getProductPairs方法，参数是all', 1 @1

- 执行product模块的getProductPairs方法，参数是all', -1 @0

- 执行product模块的getProductPairs方法，参数是all', 10001, 1 @1

- 执行product模块的getProductPairs方法，参数是, 1, 23,24 @3

- 执行product模块的getProductPairs方法，参数是noclosed', 1, 23,24 @3

- 执行product模块的getProductPairs方法，参数是noclosed', 1, 23,24', all @3

- 执行product模块的getProductPairs方法，参数是noclosed', 1, 23,24', 1 @0

- 执行product模块的getProductPairs方法，参数是noclosed', 1, 23,24', 2 @0



*/

$product = new productTest('admin');
$product->objectModel->app->user->admin = true;

r(count($product->getProductPairs()))                             && p() && e('40');
r(count($product->getProductPairs('all')))                        && p() && e('45');
r(count($product->getProductPairs('noclosed')))                   && p() && e('22');
r(count($product->getProductPairs('all', 1)))                     && p() && e('5');
r(count($product->getProductPairs('all', -1)))                    && p() && e('0');
r(count($product->getProductPairs('all', 10001, '1')))            && p() && e('1');
r(count($product->getProductPairs('', 1, '23,24')))               && p() && e('5');
r(count($product->getProductPairs('noclosed', 1, '23,24')))       && p() && e('4');
r(count($product->getProductPairs('noclosed', 1, '23,24', 'all')))&& p() && e('4');
r(count($product->getProductPairs('noclosed', 1, '23,24', '1')))  && p() && e('0');
r(count($product->getProductPairs('noclosed', 1, '23,24', '2')))  && p() && e('0');

$product->objectModel->app->user->admin = false;
$product->objectModel->app->user->view->products = '1,2,3,4,5,6,7,8,9,20,21,48,49,50';
r(count($product->getProductPairs()))                             && p() && e('9');
r(count($product->getProductPairs('all')))                        && p() && e('12');
r(count($product->getProductPairs('noclosed')))                   && p() && e('9');
r(count($product->getProductPairs('all', 1)))                     && p() && e('1');
r(count($product->getProductPairs('all', -1)))                    && p() && e('0');
r(count($product->getProductPairs('all', 10001, '1')))            && p() && e('1');
r(count($product->getProductPairs('', 1, '23,24')))               && p() && e('3');
r(count($product->getProductPairs('noclosed', 1, '23,24')))       && p() && e('3');
r(count($product->getProductPairs('noclosed', 1, '23,24', 'all')))&& p() && e('3');
r(count($product->getProductPairs('noclosed', 1, '23,24', '1')))  && p() && e('0');
r(count($product->getProductPairs('noclosed', 1, '23,24', '2')))  && p() && e('0');