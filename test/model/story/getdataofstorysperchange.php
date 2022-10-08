#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 storyModel->getDataOfStorysPerChange();
cid=1
pid=1

按照需求版本号分组，获取分组后的需求数量 >> 3
按照需求版本号分组，获取各个版本号下的需求数量，查看admin下的数据 >> 1,80
按照需求版本号分组，获取各个版本号下的需求数量，查看admin下的数据 >> 0,350

*/

global $tester;
$tester->loadModel('story');

$data = $tester->story->getDataOfStorysPerChange();

r(count($data)) && p()               && e('3');     // 按照需求版本号分组，获取分组后的需求数量
r($data)        && p('1:name,value') && e('1,80');  // 按照需求版本号分组，获取各个版本号下的需求数量，查看admin下的数据
r($data)        && p('2:name,value') && e('0,350'); // 按照需求版本号分组，获取各个版本号下的需求数量，查看admin下的数据