#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->getDataOfStorysPerStatus();
cid=1
pid=1

按照需求状态分组，获取分组后的需求数量 >> 4
按照需求状态分组，获取各个需求状态的需求数量，查看draft下的数据 >> 草稿,184
按照需求状态分组，获取各个需求状态的需求数量，查看active下的数据 >> 激活,103
按照需求状态分组，获取各个需求状态的需求数量，查看closed下的数据 >> 已关闭,82

*/

global $tester;
$tester->loadModel('story');
$tester->loadModel('report');

$data = $tester->story->getDataOfStorysPerStatus();

r(count($data)) && p()                    && e('4');         // 按照需求状态分组，获取分组后的需求数量
r($data)        && p('draft:name,value')  && e('草稿,184');  // 按照需求状态分组，获取各个需求状态的需求数量，查看draft下的数据
r($data)        && p('active:name,value') && e('激活,103');  // 按照需求状态分组，获取各个需求状态的需求数量，查看active下的数据
r($data)        && p('closed:name,value') && e('已关闭,82'); // 按照需求状态分组，获取各个需求状态的需求数量，查看closed下的数据