#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/productplan.class.php';

zdTable('branch')->config('relationbranch_branch')->gen(20);
zdTable('productplan')->config('relationbranch_productplan')->gen(50);

/**

title=productplanModel->relationBranch();
cid=1
pid=1

*/

$plan = new productPlan('admin');

$planID = array();
$planID[0] = array(3, 13, 14);

$plans = $plan->relationBranchTest($planID[0]);

r($plan->relationBranchTest(array()))  && p() && e('0');      //不传入任何数据
r($plans[3])  && p('branchName') && e('主干');                //关联主干分支
r($plans[13]) && p('branchName', '|') && e('分支10,分支13');  //关联分支10,分支13
r($plans[14]) && p('branchName', '|') && e('主干,分支14');    //关联主干,分支14
