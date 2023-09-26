#!/usr/bin/env php
<?php
/**

title=productpanModel->getBranchPlanPairs();
timeout=0
cid=1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/productplan.class.php';

zdTable('productplan')->config('productplan')->gen(20);
$plan = new productPlan('admin');

$product = array();
$product[0] = 6;
$product[1] = 111;

$branch  = array();
$branch[0]  = 2;
$branch[1]  = 111;

r($plan->getBranchPlanPairs($product[0], $branch[0])) && p('2:17') && e('计划17 [2021-06-01~2021-06-15]'); //传入存在的数据，返回相应信息
r($plan->getBranchPlanPairs($product[1], $branch[1])) && p() && e('0');                                    //传入不存在的分支
r($plan->getBranchPlanPairs($product[1], $branch[0])) && p() && e('0');                                    //传入不存在的产品id
