#!/usr/bin/env php
<?php

/**

title=测试executionModel->update() 编辑必填关联产品计划;
timeout=0
cid=1

- 关联产品必填且未选择产品属性products @『关联产品』不能为空。
- 关联计划必填且未选择计划属性plans @『关联计划』不能为空。
- 关联产品与计划均已填写可保存
 - 第0条的field属性 @days
 - 第0条的old属性 @0
 - 第0条的new属性 @5
- 无产品项目不校验关联产品
 - 第0条的field属性 @planDuration
 - 第0条的old属性 @0
 - 第0条的new属性 @30

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$execution = zenData('project');
$execution->id->range('101-104');
$execution->name->range('项目A,项目B,执行A,执行B');
$execution->code->range('projA,projB,execA,execB');
$execution->type->range('project{2},sprint{2}');
$execution->project->range('0{2},101,102');
$execution->hasProduct->range('1,0,1,0');
$execution->status->range('doing');
$execution->parent->range('0{2},101,102');
$execution->days->range('0');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->deleted->range('0');
$execution->gen(4);

zenData('lang')->gen(0);
su('admin');

$execution = new executionModelTest();
include($execution->executionModel->app->getBasePath() . 'module/execution/lang/zh-cn.php');

$emptyProducts = array('products' => array());
$emptyPlans    = array('products' => array(1), 'plans' => array());
$bothFilled    = array('products' => array(1), 'plans' => array(1 => array(1)), 'days' => '5');
$skipProduct   = array('products' => array());

r($execution->updateRequiredProductPlanTest(103, $emptyProducts, 'name,begin,end,products')) && p('products') && e('『关联产品』不能为空。'); // 关联产品必填且未选择产品
r($execution->updateRequiredProductPlanTest(103, $emptyPlans, 'name,begin,end,plans'))       && p('plans')    && e('『关联计划』不能为空。'); // 关联计划必填且未选择计划
r($execution->updateRequiredProductPlanTest(103, $bothFilled, 'name,begin,end,products,plans')) && p('0:field,old,new') && e('days,0,5'); // 关联产品与计划均已填写可保存
r($execution->updateRequiredProductPlanTest(104, $skipProduct, 'name,begin,end,products'))   && p('0:field,old,new') && e('planDuration,0,30'); // 无产品项目不校验关联产品
