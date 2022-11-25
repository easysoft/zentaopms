#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/productplan.class.php';

/**

title=productpanModel->getBranchPlanPairs();
cid=1
pid=1

传入存在的数据，返回相应信息 >> 1.0 [2021-11-18~2022-09-30]
传入不存在的分支 >> 0
传入不存在的产品id >> 0

*/
$plan = new productPlan('admin');

$product = array();
$product[0] = 41;
$product[1] = 1;
$product[2] = 111;

$branch  = array();
$branch[0]  = 1;
$branch[1]  = 111;
$branch[2]  = 1;

r($plan->getBranchPlanPairs($product[0], $branch[0])) && p('1:31') && e('1.0 [2021-11-18~2022-09-30]'); //传入存在的数据，返回相应信息
r($plan->getBranchPlanPairs($product[1], $branch[1])) && p('1:31') && e('0');                           //传入不存在的分支
r($plan->getBranchPlanPairs($product[2], $branch[2])) && p('1:31') && e('0');                           //传入不存在的产品id
?>
