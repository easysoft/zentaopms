#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/programplan.class.php';
su('admin');

/**

title=测试 programplanModel->update();
cid=1
pid=1

测试修改plan的 percent 值 工作占比和超过100 >> 工作量占比累计不应当超过100%
测试修改plan的 end 值 计划完成小于计划开始 >> 『end』应当不小于。
测试修改plan的 name 值 名称为空 >> 『name』不能为空。
测试修改plan的 name 值 >> name,修改后的阶段
测试修改plan的 percent 值 >> percent,15
测试修改plan的 begin 值 >> begin,2022-04-01
测试修改plan的 end 值 >> end,2022-04-30

*/
$planID    = 131;
$projectID = 41;

$beginNotempty = array('begin' => '');
$endNotempty   = array('end' => '');
$percentOver   = array('percent' => 90);
$endLeBegin    = array('end' => '2022-01-01');
$nameNotempty  = array('name' => '');
$changeName    = array('name' => '修改后的阶段');
$changePercent = array('percent' => '15');
$changeBegin   = array('begin' => '2022-04-01');
$changeEnd     = array('end' => '2022-04-30');

$programplan = new programplanTest();

//r($programplan->updateTest($planID, $projectID, $beginNotempty, 'begin')) && p() && e(''); // 测试修改plan的 begin 值 计划开始为空
//r($programplan->updateTest($planID, $projectID, $endNotempty, 'end'))     && p() && e(''); // 测试修改plan的 end 值 计划完成为空
r($programplan->updateTest($planID, $projectID, $percentOver, 'percent')) && p()              && e('工作量占比累计不应当超过100%'); // 测试修改plan的 percent 值 工作占比和超过100
r($programplan->updateTest($planID, $projectID, $endLeBegin, 'end'))      && p()              && e('『end』应当不小于。');          // 测试修改plan的 end 值 计划完成小于计划开始
r($programplan->updateTest($planID, $projectID, $nameNotempty, 'name'))   && p()              && e('『name』不能为空。');           // 测试修改plan的 name 值 名称为空
r($programplan->updateTest($planID, $projectID, $changeName))             && p('0:field,new') && e('name,修改后的阶段');            // 测试修改plan的 name 值
r($programplan->updateTest($planID, $projectID, $changePercent))          && p('0:field,new') && e('percent,15');                   // 测试修改plan的 percent 值
r($programplan->updateTest($planID, $projectID, $changeBegin))            && p('0:field,new') && e('begin,2022-04-01');             // 测试修改plan的 begin 值
r($programplan->updateTest($planID, $projectID, $changeEnd))              && p('0:field,new') && e('end,2022-04-30');               // 测试修改plan的 end 值
