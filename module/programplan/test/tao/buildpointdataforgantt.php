#!/usr/bin/env php
<?php

/**

title=测试 loadModel->buildPointDataForGantt()
cid=0

- 禅道版本为IPD。
 - 属性id @1-PP-1
 - 属性reviewID @1
 - 属性type @point
 - 属性text @<i class='icon-seal'></i> 这个是评审或基线的标题1

*/

include dirname(__FILE__, 5). '/test/lib/init.php';
su('admin');

zdTable('review')->gen(20);
zdTable('object')->gen(20);
$project = zdTable('project');
$project->type->range('stage');
$project->attribute->range('devel');
$project->begin->range('`2023-09-28`');
$project->end->range('`2024-04-02`');
$project->gen(10);

global $tester;
$tester->loadModel('programplan');

$point = $tester->programplan->dao->select('t1.*, t2.status, t2.lastReviewedDate,t2.id as reviewID')->from(TABLE_OBJECT)->alias('t1')
    ->leftJoin(TABLE_REVIEW)->alias('t2')->on('t1.id = t2.object')
    ->where('t1.deleted')->eq('0')
    ->andWhere('t1.project')->eq(1)
    ->andWhere('t1.product')->eq(1)
    ->fetch();

$plans = $tester->programplan->dao->select('*')->from(TABLE_PROJECT)->where('type')->eq('stage')->fetchAll('id');
$plans = $tester->programplan->processPlans($plans);

$normalResult = $tester->programplan->initGanttPlans($plans);

r($tester->programplan->buildPointDataForGantt(1, $point, $normalResult['reviewDeadline'])) && p('id,reviewID,type,text') && e("1-PP-1,1,point,<i class='icon-seal'></i> 这个是评审或基线的标题1"); //禅道版本为IPD。
