#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 storyModel->getDataOfStorysPerOpenedBy();
cid=1
pid=1

按照创建人分组，获取分组后的需求数量 >> 5
按照创建人分组，获取各个创建人的需求数量，查看用户test3下的数据 >> 开发3,100

*/

global $tester;
$tester->loadModel('story');
$tester->loadModel('report');

$data = $tester->story->getDataOfStorysPerOpenedBy();

r(count($data)) && p()                   && e('5');         // 按照创建人分组，获取分组后的需求数量
r($data)        && p('test3:name,value') && e('开发3,100'); // 按照创建人分组，获取各个创建人的需求数量，查看用户test3下的数据