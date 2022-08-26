#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->getDataOfStorysPerPri();
cid=1
pid=1

按照优先级分组，获取分组后的需求数量 >> 4
按照优先级分组，获取各个优先级的需求数量，查看优先级1下的数据 >> 1,113
按照优先级分组，获取各个优先级的需求数量，查看优先级4下的数据 >> 1,112

*/

global $tester;
$tester->loadModel('story');
$tester->loadModel('report');

$data = $tester->story->getDataOfStorysPerPri();

r(count($data)) && p()                && e('4');     // 按照优先级分组，获取分组后的需求数量
r($data)        && p('1:name,value')  && e('1,113'); // 按照优先级分组，获取各个优先级的需求数量，查看优先级1下的数据
r($data)        && p('4:name,value')  && e('1,112'); // 按照优先级分组，获取各个优先级的需求数量，查看优先级4下的数据