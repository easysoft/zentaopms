#!/usr/bin/env php
<?php

/**

title=测试productModel->isClickable();
cid=0

- 产品1状态为为normal,action为start @1
- 产品2状态为为normal,action为start @1
- 产品3状态为为normal,action为start @1
- 产品1状态为为normal,action为close @1
- 产品2状态为为normal,action为close @1
- 产品3状态为为normal,action为close @1
- 产品4状态为为closed,action为start @1
- 产品5状态为为closed,action为start @1
- 产品6状态为为closed,action为start @1
- 产品4状态为为closed,action为close @0
- 产品5状态为为closed,action为close @0
- 产品6状态为为closed,action为close @0
- 产品1状态为为normal,action为START @1
- 产品2状态为为normal,action为CLOSE @1
- 产品4状态为为closed,action为START @1
- 产品5状态为为closed,action为CLOSE @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';
su('admin');

zdTable('product')->config('product')->gen(6);
$product = new productTest('admin');

$normalProducts = array(1, 2, 3);
$closedProducts = array(4, 5, 6);

r($product->testIsClickable($normalProducts[0], 'start')) && p() && e('1');  // 产品1状态为为normal,action为start
r($product->testIsClickable($normalProducts[1], 'start')) && p() && e('1');  // 产品2状态为为normal,action为start
r($product->testIsClickable($normalProducts[2], 'start')) && p() && e('1');  // 产品3状态为为normal,action为start
r($product->testIsClickable($normalProducts[0], 'close')) && p() && e('1');  // 产品1状态为为normal,action为close
r($product->testIsClickable($normalProducts[1], 'close')) && p() && e('1');  // 产品2状态为为normal,action为close
r($product->testIsClickable($normalProducts[2], 'close')) && p() && e('1');  // 产品3状态为为normal,action为close

r($product->testIsClickable($closedProducts[0], 'start')) && p() && e('1');  // 产品4状态为为closed,action为start
r($product->testIsClickable($closedProducts[1], 'start')) && p() && e('1');  // 产品5状态为为closed,action为start
r($product->testIsClickable($closedProducts[2], 'start')) && p() && e('1');  // 产品6状态为为closed,action为start
r($product->testIsClickable($closedProducts[0], 'close')) && p() && e('0');  // 产品4状态为为closed,action为close
r($product->testIsClickable($closedProducts[1], 'close')) && p() && e('0');  // 产品5状态为为closed,action为close
r($product->testIsClickable($closedProducts[2], 'close')) && p() && e('0');  // 产品6状态为为closed,action为close

r($product->testIsClickable($normalProducts[0], 'START')) && p() && e('1');  // 产品1状态为为normal,action为START
r($product->testIsClickable($normalProducts[1], 'CLOSE')) && p() && e('1');  // 产品2状态为为normal,action为CLOSE
r($product->testIsClickable($closedProducts[0], 'START')) && p() && e('1');  // 产品4状态为为closed,action为START
r($product->testIsClickable($closedProducts[1], 'CLOSE')) && p() && e('0');  // 产品5状态为为closed,action为CLOSE
