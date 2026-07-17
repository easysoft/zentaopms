#!/usr/bin/env php
<?php

/**

title=测试 loadModel->buildGanttData4IPD()
cid=17760

- 禅道版本为开源版。 @0
- gantt数据数。 @11
- 禅道版本为IPD。
 - 属性id @1-pointPP-1
 - 属性reviewID @1
 - 属性type @point
 - 属性text @<i class='icon-seal'></i> 这个是评审或基线的标题1
- selectCustom 不包含 point。 @0

*/

include dirname(__FILE__, 5). '/test/lib/init.php';
su('admin');

zenData('review')->gen(0);
$project = zenData('project');
$project->type->range('stage');
$project->attribute->range('devel');
$project->begin->range('`2023-09-28`');
$project->end->range('`2024-04-02`');
$project->gen(10);

$object = zenData('object');
$object->id->range('1');
$object->project->range('1');
$object->execution->range('1');
$object->type->range('decision');
$object->category->range('PP');
$object->title->range('这个是评审或基线的标题1');
$object->deleted->range('0');
$object->enabled->range('1');
$object->gen(1);

$review = zenData('review');
$review->id->range('1');
$review->object->range('1');
$review->type->range('decision');
$review->status->range('draft');
$review->deleted->range('0');
$review->gen(1);

$product = zenData('product');
$product->id->range('1');
$product->name->range('产品1');
$product->status->range('normal');
$product->gen(1);

global $tester;
$tester->loadModel('programplan');

$plans = $tester->programplan->dao->select('*')->from(TABLE_PROJECT)->where('type')->eq('stage')->fetchAll('id');
$plans = $tester->programplan->processPlans($plans);

$normalResult = $tester->programplan->initGanttPlans($plans);

$tester->programplan->config->edition = 'open';
$datas = $tester->programplan->buildGanttData4IPD($normalResult['datas'], 1, 1, 'point', $normalResult['reviewDeadline']);
r(isset($datas['data']['1-pointPP-1'])) && p() && e("0"); //禅道版本为开源版。

$tester->programplan->config->edition = 'ipd';
$datas = $tester->programplan->buildGanttData4IPD($normalResult['datas'], 1, 1, 'point', $normalResult['reviewDeadline']);
r(count($datas['data']))         && p()                        && e('11');                                                               //gantt数据数。
r($datas['data']['1-pointPP-1']) && p('id,reviewID,type,text') && e("1-pointPP-1,1,point,<i class='icon-seal'></i> 这个是评审或基线的标题1"); //禅道版本为IPD。

$datas = $tester->programplan->buildGanttData4IPD($normalResult['datas'], 1, 1, '', $normalResult['reviewDeadline']);
r(isset($datas['data']['1-pointPP-1'])) && p() && e("0"); //selectCustom 不包含 point。
