#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/productplan.class.php';

/**

title=productpanModel->getPairs();
cid=1
pid=1

id为41的产品分支32 >> 分支2 / 1.1 [2021-11-25 ~ 2022-10-07]
id为41的产品分支31 >> 分支1 / 1.0 [2021-11-18 ~ 2022-09-30]
id为42的产品分支34 >> 分支4 / 1.0 [2021-12-09 ~ 2022-10-21]

*/

$plan = new productPlan('admin');

$product    = array();
$product[0] = 41;
$product[1] = 42;
$branch     = 1;
$expired    = 'unexpired';

r($plan->getPairs($product[0], $branch, $expired)) && p('32') && e('分支2 / 1.1 [2021-11-25 ~ 2022-10-07]'); //id为41的产品分支32
r($plan->getPairs($product[0], $branch, $expired)) && p('31') && e('分支1 / 1.0 [2021-11-18 ~ 2022-09-30]'); //id为41的产品分支31
r($plan->getPairs($product[1], $branch, $expired)) && p('34') && e('分支4 / 1.0 [2021-12-09 ~ 2022-10-21]'); //id为42的产品分支34
?>
