#!/usr/bin/env php
<?php
/**

title=productplanModel->relationBranch();
timeout=0
cid=17645

- 不传入任何数据 @0
- 关联主干分支属性branchName @主干
- 关联主干分支属性branchName @主干
- 关联主干分支属性branchName @分支2
- 关联主干分支属性branchName @分支5
- 关联分支10,分支13属性branchName @分支10,分支13
- 关联主干,分支14属性branchName @主干,分支14

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('branch')->loadYaml('relationbranch_branch')->gen(20);
zenData('productplan')->loadYaml('relationbranch_productplan')->gen(50);

$plan = new productPlan('admin');

$planID = array();
$planID[0] = array(1,3, 5, 8, 13, 14);

$plans = $plan->relationBranchTest($planID[0]);

r($plan->relationBranchTest(array())) && p() && e('0');       //不传入任何数据
r($plans[1])  && p('branchName')      && e('主干');           //关联主干分支
r($plans[3])  && p('branchName')      && e('主干');           //关联主干分支
r($plans[5])  && p('branchName')      && e('分支2');          //关联主干分支
r($plans[8])  && p('branchName')      && e('分支5');          //关联主干分支
r($plans[13]) && p('branchName', '|') && e('分支10,分支13');  //关联分支10,分支13
r($plans[14]) && p('branchName', '|') && e('主干,分支14');    //关联主干,分支14
