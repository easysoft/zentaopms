#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/productplan.class.php';

/**

title=productpanModel->getGroupByProduct();
cid=1
pid=1

传入正常product 1，2； >> 2
传入部分不存在product； >> 1
传入不存在product >> 0
传入正常product 1，2；未过期param >> 0
传入部分不存在product；未过期param >> 0
传入不存在product； 未过期param >> 0

*/

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
r($plan->getGroupByProduct($products[0], $param[1])) && p() && e('0'); //传入正常product 1，2；未过期param
r($plan->getGroupByProduct($products[1], $param[1])) && p() && e('0'); //传入部分不存在product；未过期param
r($plan->getGroupByProduct($products[2], $param[1])) && p() && e('0'); //传入不存在product； 未过期param
?>