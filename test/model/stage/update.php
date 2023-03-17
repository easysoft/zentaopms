#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/stage.class.php';
su('admin');

/**

title=测试 stageModel->update();
cid=1
pid=1

测试修改名称 >> name,需求,修改后的需求
测试修改工作量占比 >> percent,10,15
测试修改阶段分类 >> type,request,other

*/

$stageID = 1;

$changeName    = array('name' => '修改后的需求');
$changePercent = array('percent' => '15');
$changeType    = array('type' => 'other');

$stage = new stageTest();

r($stage->updateTest($stageID, $changeName))    && p('0:field,old,new') && e('name,需求,修改后的需求'); // 测试修改名称
r($stage->updateTest($stageID, $changePercent)) && p('0:field,old,new') && e('percent,10,15');          // 测试修改工作量占比
r($stage->updateTest($stageID, $changeType))    && p('0:field,old,new') && e('type,request,other');     // 测试修改阶段分类
