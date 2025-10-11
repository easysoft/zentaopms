#!/usr/bin/env php
<?php

/**

title=测试 loadModel->buildCreateView()
timeout=0
cid=0

- 获取title @设置阶段-项目12
- 获取productList属性2 @正常产品2
- 获取project
 - 属性id @12
 - 属性name @项目12
- 获取stages @0
- 获取type @lists

*/

include dirname(__FILE__, 5). '/test/lib/init.php';
su('admin');

zenData('project')->gen(20);
zenData('product')->gen(10);
zenData('projectproduct')->gen(10);

$programplanModel = $tester->loadModel('programplan');
$project = $programplanModel->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq(12)->fetch();

$viewData = new stdclass();
$viewData->productID     = 0;
$viewData->planID        = 0;
$viewData->executionType = 'stage';
$viewData->programPlan   = '';
$viewData->productList   = array(2 => '正常产品2');
$viewData->project       = $project;
$viewData->plans         = array();
$viewData->syncData      = 0;

global $tester;
$tester->loadModel('programplan');
$tester->app->setModuleName('programplan');

initReference('programplan');
$result = callZenMethod('programplan', 'buildCreateView', [$viewData], 'view');

r($result->title)       && p()          && e('设置阶段-项目12'); //获取title
r($result->productList) && p('2')       && e('正常产品2');       //获取productList
r($result->project)     && p('id,name') && e('12,项目12');       //获取project
r($result->stages)      && p()          && e('0');               //获取stages
r($result->type)        && p()          && e('lists');           //获取type
