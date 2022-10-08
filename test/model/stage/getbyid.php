#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/stage.class.php';
su('admin');

/**

title=测试 stageModel->getByID();
cid=1
pid=1

测试获取阶段 1 >> 需求,10,request
测试获取阶段 2 >> 设计,10,design
测试获取阶段 3 >> 开发,50,dev
测试获取阶段 4 >> 测试,15,qa
测试获取阶段 5 >> 发布,10,release
测试获取阶段 6 >> 总结评审,5,review

*/

$stageIDList = array(1, 2, 3, 4, 5, 6);

$stage = new stageTest();

r($stage->getByIDTest($stageIDList[0])) && p('name,percent,type') && e('需求,10,request');   // 测试获取阶段 1
r($stage->getByIDTest($stageIDList[1])) && p('name,percent,type') && e('设计,10,design');    // 测试获取阶段 2
r($stage->getByIDTest($stageIDList[2])) && p('name,percent,type') && e('开发,50,dev');       // 测试获取阶段 3
r($stage->getByIDTest($stageIDList[3])) && p('name,percent,type') && e('测试,15,qa');        // 测试获取阶段 4
r($stage->getByIDTest($stageIDList[4])) && p('name,percent,type') && e('发布,10,release');   // 测试获取阶段 5
r($stage->getByIDTest($stageIDList[5])) && p('name,percent,type') && e('总结评审,5,review'); // 测试获取阶段 6