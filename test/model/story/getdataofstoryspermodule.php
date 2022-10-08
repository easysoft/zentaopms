#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->getDataOfStorysPerModule();
cid=1
pid=1

按照模块分组，获取分组后的需求数量 >> 100
按照模块分组，获取各个模块下的需求数量，查看模块2150下的数据 >> /产品模块330,1,83

*/

global $tester;
$tester->loadModel('story');
$tester->loadModel('report');

$data = $tester->story->getDataOfStorysPerModule();

r(count($data)) && p()                          && e('100');               // 按照模块分组，获取分组后的需求数量
r($data)        && p('2150:name,value,product') && e('/产品模块330,1,83'); // 按照模块分组，获取各个模块下的需求数量，查看模块2150下的数据