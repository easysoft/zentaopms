#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 storyModel->getDataOfStorysPerPlan();
cid=1
pid=1

按照计划分组，获取分组后的需求数量 >> 25
按照计划分组，获取各个计划的需求数量，查看1下的数据 >> 1.0,18
按照计划分组，获取各个计划的需求数量，查看70下的数据 >> 1.0,15

*/

global $tester;
$tester->loadModel('story');
$tester->loadModel('report');

$data = $tester->story->getDataOfStorysPerPlan();

r(count($data)) && p()                && e('25');     // 按照计划分组，获取分组后的需求数量
r($data)        && p('1:name,value')  && e('1.0,18'); // 按照计划分组，获取各个计划的需求数量，查看1下的数据
r($data)        && p('70:name,value') && e('1.0,15'); // 按照计划分组，获取各个计划的需求数量，查看70下的数据