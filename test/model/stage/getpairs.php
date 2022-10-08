#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/stage.class.php';
su('admin');

/**

title=测试 stageModel->getPairs();
cid=1
pid=1

测试获取阶段 1 >> 需求
测试获取阶段 2 >> 设计
测试获取阶段 3 >> 开发
测试获取阶段 4 >> 测试
测试获取阶段 5 >> 发布
测试获取阶段 6 >> 总结评审

*/

$stage = new stageTest();

r($stage->getPairsTest()) && p('1') && e('需求');     // 测试获取阶段 1
r($stage->getPairsTest()) && p('2') && e('设计');     // 测试获取阶段 2
r($stage->getPairsTest()) && p('3') && e('开发');     // 测试获取阶段 3
r($stage->getPairsTest()) && p('4') && e('测试');     // 测试获取阶段 4
r($stage->getPairsTest()) && p('5') && e('发布');     // 测试获取阶段 5
r($stage->getPairsTest()) && p('6') && e('总结评审'); // 测试获取阶段 6