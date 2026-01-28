#!/usr/bin/env php
<?php
/**

title=productpanModel->getGroupByProduct();
timeout=0
cid=17634

- 传入正常product 1，2； @2
- 传入部分不存在product； @1
- 传入不存在product @0
- 传入正常product 1，2；未过期param @1
- 传入部分不存在product；未过期param @1
- 传入不存在product； 未过期param @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('branch')->loadYaml('branch')->gen(10);
zenData('productplan')->loadYaml('productplan')->gen(10);

$plan = new productPlan('admin');

$products = array();
$products[0] = array(1, 2);
$products[1] = array(2, 10000);
$products[2] = array(9999, 10000);

$param = array();
$param[0] = 'skipParent';
$param[1] = 'unexpired';

r($plan->getGroupByProduct($products[0], $param[0])) && p() && e('2'); //传入正常product 1，2；
r($plan->getGroupByProduct($products[1], $param[0])) && p() && e('1'); //传入部分不存在product；
r($plan->getGroupByProduct($products[2], $param[0])) && p() && e('0'); //传入不存在product
r($plan->getGroupByProduct($products[0], $param[1])) && p() && e('1'); //传入正常product 1，2；未过期param
r($plan->getGroupByProduct($products[1], $param[1])) && p() && e('1'); //传入部分不存在product；未过期param
r($plan->getGroupByProduct($products[2], $param[1])) && p() && e('0'); //传入不存在product； 未过期param
?>
