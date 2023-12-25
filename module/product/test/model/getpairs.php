#!/usr/bin/env php
<?php

/**

title=测试productModel->getPairs();
cid=0

- 管理员获取未删除状态和非影子产品的产品，删除状态5个(ID:46-50)，影子产品5个(ID:18-22) @40
- 管理员获取未删除的全部产品 @45
- 管理员获取未关闭的产品 @22
- 管理员获取programID为1的所有（未删除）产品 @5
- 管理员获取programID不存在的所有（未删除）产品 @0
- 管理员选择programID不存在或ID为1的产品 @1
- 管理员选择programID为10001或ID为1的产品 @1
- 管理员选择programID为1且未删除和非影子产品的产品，或ID为23,24的产品 @5
- 管理员选择programID为1且未删除和非影子产品的产品，或ID为23,24格式是数组的产品 @5
- 管理员选择未关闭的且programID为1，或ID为23，24的产品 @4
- 管理员选择未关闭的且programID为1且包含所有影子产品，或ID为23,24的产品 @4
- 管理员选择未关闭的且programID为1且包含影子产品ID1，或ID为23,24的产品 @0
- 管理员选择未关闭的且programID为1且包含影子产品ID2，或ID为23,24的产品 @0
- 普通用户获取未删除状态和非影子产品的产品，删除状态5个(ID:46-50)，影子产品5个(ID:18-22)，符合的只有ID:1-9 @9
- 普通用户获取未删除的全部产品 @12
- 普通用户获取未关闭的产品 @9
- 普通用户获取programID为1的所有（未删除）产品 @1
- 普通用户获取programID不存在的所有（未删除）产品 @0
- 普通用户选择programID不存在或ID为1的产品 @1
- 普通用户选择programID为10001或ID为1的产品 @1
- 普通用户选择programID为1且未删除和非影子产品的产品，或ID为23,24的产品 @3
- 普通用户选择未关闭的且programID为1，或ID为23，24的产品 @3
- 普通用户选择未关闭的且programID为1且包含所有影子产品，或ID为23,24的产品 @3
- 普通用户选择未关闭的且programID为1且包含影子产品ID1，或ID为23,24的产品 @0
- 普通用户选择未关闭的且programID为1且包含影子产品ID2，或ID为23,24的产品 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('product')->gen(50);

$product = new productTest('admin');
$product->objectModel->app->user->admin = true;

r(count($product->getProductPairs()))                             && p() && e('40'); // 管理员获取未删除状态和非影子产品的产品，删除状态5个(ID:46-50)，影子产品5个(ID:18-22)
r(count($product->getProductPairs('all')))                        && p() && e('45'); // 管理员获取未删除的全部产品
r(count($product->getProductPairs('noclosed')))                   && p() && e('22'); // 管理员获取未关闭的产品
r(count($product->getProductPairs('all', 1)))                     && p() && e('5');  // 管理员获取programID为1的所有（未删除）产品
r(count($product->getProductPairs('all', -1)))                    && p() && e('0');  // 管理员获取programID不存在的所有（未删除）产品
r(count($product->getProductPairs('all', -1, '1')))               && p() && e('1');  // 管理员选择programID不存在或ID为1的产品
r(count($product->getProductPairs('all', 10001, '1')))            && p() && e('1');  // 管理员选择programID为10001或ID为1的产品
r(count($product->getProductPairs('', 1, '23,24')))               && p() && e('5');  // 管理员选择programID为1且未删除和非影子产品的产品，或ID为23,24的产品
r(count($product->getProductPairs('', 1, array(23,24))))          && p() && e('5');  // 管理员选择programID为1且未删除和非影子产品的产品，或ID为23,24格式是数组的产品
r(count($product->getProductPairs('noclosed', 1, '23,24')))       && p() && e('4');  // 管理员选择未关闭的且programID为1，或ID为23，24的产品
r(count($product->getProductPairs('noclosed', 1, '23,24', 'all')))&& p() && e('4');  // 管理员选择未关闭的且programID为1且包含所有影子产品，或ID为23,24的产品
r(count($product->getProductPairs('noclosed', 1, '23,24', '1')))  && p() && e('0');  // 管理员选择未关闭的且programID为1且包含影子产品ID1，或ID为23,24的产品
r(count($product->getProductPairs('noclosed', 1, '23,24', '2')))  && p() && e('0');  // 管理员选择未关闭的且programID为1且包含影子产品ID2，或ID为23,24的产品

$product->objectModel->app->user->admin = false;
$product->objectModel->app->user->view->products = '1,2,3,4,5,6,7,8,9,20,21,48,49,50';
r(count($product->getProductPairs()))                             && p() && e('9');    // 普通用户获取未删除状态和非影子产品的产品，删除状态5个(ID:46-50)，影子产品5个(ID:18-22)，符合的只有ID:1-9
r(count($product->getProductPairs('all')))                        && p() && e('12');   // 普通用户获取未删除的全部产品
r(count($product->getProductPairs('noclosed')))                   && p() && e('9');    // 普通用户获取未关闭的产品
r(count($product->getProductPairs('all', 1)))                     && p() && e('1');    // 普通用户获取programID为1的所有（未删除）产品
r(count($product->getProductPairs('all', -1)))                    && p() && e('0');    // 普通用户获取programID不存在的所有（未删除）产品
r(count($product->getProductPairs('all', -1, '1')))               && p() && e('1');    // 普通用户选择programID不存在或ID为1的产品
r(count($product->getProductPairs('all', 10001, '1')))            && p() && e('1');    // 普通用户选择programID为10001或ID为1的产品
r(count($product->getProductPairs('', 1, '23,24')))               && p() && e('3');    // 普通用户选择programID为1且未删除和非影子产品的产品，或ID为23,24的产品
r(count($product->getProductPairs('noclosed', 1, '23,24')))       && p() && e('3');    // 普通用户选择未关闭的且programID为1，或ID为23，24的产品
r(count($product->getProductPairs('noclosed', 1, '23,24', 'all')))&& p() && e('3');    // 普通用户选择未关闭的且programID为1且包含所有影子产品，或ID为23,24的产品
r(count($product->getProductPairs('noclosed', 1, '23,24', '1')))  && p() && e('0');    // 普通用户选择未关闭的且programID为1且包含影子产品ID1，或ID为23,24的产品
r(count($product->getProductPairs('noclosed', 1, '23,24', '2')))  && p() && e('0');    // 普通用户选择未关闭的且programID为1且包含影子产品ID2，或ID为23,24的产品
