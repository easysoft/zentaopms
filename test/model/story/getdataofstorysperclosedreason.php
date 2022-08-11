#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 storyModel->getDataOfStorysPerClosedReason();
cid=1
pid=1

按照需求关闭原因分组，获取分组后的需求数量 >> 8
按照需求关闭原因分组，获取各个关闭原因下的需求数量，查看willnotdo的数据 >> 不做,12
按照需求关闭原因分组，获取各个关闭原因下的需求数量，查看bydesign的数据 >> 设计如此,12
按照需求关闭原因分组，获取各个关闭原因下的需求数量，查看cancel的数据 >> 已取消,11

*/

global $tester;
$tester->loadModel('story');
$tester->loadModel('report');

$data = $tester->story->getDataOfStorysPerClosedReason();

r(count($data)) && p()                       && e('8');           // 按照需求关闭原因分组，获取分组后的需求数量
r($data)        && p('willnotdo:name,value') && e('不做,12');     // 按照需求关闭原因分组，获取各个关闭原因下的需求数量，查看willnotdo的数据
r($data)        && p('bydesign:name,value')  && e('设计如此,12'); // 按照需求关闭原因分组，获取各个关闭原因下的需求数量，查看bydesign的数据
r($data)        && p('cancel:name,value')    && e('已取消,11');   // 按照需求关闭原因分组，获取各个关闭原因下的需求数量，查看cancel的数据