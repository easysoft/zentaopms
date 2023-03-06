#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/stage.class.php';
su('admin');

/**

title=测试 stageModel->getStages();
cid=1
pid=1

测试获取阶段 1 >> 需求,10,request
测试获取阶段 2 >> 设计,10,design
测试获取阶段 3 >> 开发,50,dev
测试获取阶段 4 >> 测试,15,qa
测试获取阶段 5 >> 发布,10,release
测试获取阶段 6 >> 总结评审,5,review

*/

zdTable('stage')->gen(10);

$stage = new stageTest();

r($stage->getStagesTest()) && p('1:name,percent,type') && e('需求,10,request');   // 测试获取阶段 1
r($stage->getStagesTest()) && p('2:name,percent,type') && e('设计,10,design');    // 测试获取阶段 2
r($stage->getStagesTest()) && p('3:name,percent,type') && e('开发,50,dev');       // 测试获取阶段 3
r($stage->getStagesTest()) && p('4:name,percent,type') && e('测试,15,qa');        // 测试获取阶段 4
r($stage->getStagesTest()) && p('5:name,percent,type') && e('发布,10,release');   // 测试获取阶段 5
r($stage->getStagesTest()) && p('6:name,percent,type') && e('总结评审,5,review'); // 测试获取阶段 6
