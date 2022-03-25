#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/productplan.class.php';

$plan = new productPlan('admin');

$product    = array();
$product[0] = 41;
$product[1] = 42;
$branch     = 1;
$expired    = 'unexpired';

r($plan->getPairs($product[0], $branch, $expired)) && p('32') && e('分支2 / 1.1 [2021-06-08 ~ 2022-04-23]'); //id为41的产品分支32
r($plan->getPairs($product[0], $branch, $expired)) && p('31') && e('分支1 / 1.0 [2021-06-01 ~ 2022-04-16]'); //id为41的产品分支31
r($plan->getPairs($product[1], $branch, $expired)) && p('34') && e('分支4 / 1.0 [2021-06-22 ~ 2022-05-07]'); //id为42的产品分支34
?>
