#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 storyModel->getDataOfStoriesPerAssignedTo();
cid=1
pid=1

按照需求指派人分组，获取分组后的需求数量 >> 5
按照需求指派人分组，获取各个指派人名下的需求数量，查看admin下的数据 >> admin,100

*/

global $tester;
$tester->loadModel('story');
$tester->loadModel('report');

$data = $tester->story->getDataOfStoriesPerAssignedTo();

r(count($data)) && p()                   && e('5');         // 按照需求指派人分组，获取分组后的需求数量
r($data)        && p('admin:name,value') && e('admin,100'); // 按照需求指派人分组，获取各个指派人名下的需求数量，查看admin下的数据