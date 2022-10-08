#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 storyModel->getZeroCase();
cid=1
pid=1

获取产品1下的零用例需求数量 >> 1
获取产品2下的零用例需求数量 >> 1
获取产品0下的零用例需求数量 >> 0
获取产品1下的零用例需求详情 >> 软件需求4,active,story,planned
获取产品2下的零用例需求详情 >> 软件需求8,active,story,developing

*/

global $tester;
$tester->loadModel('story');
$zeroCaseStory1 = $tester->story->getZeroCase(1);
$zeroCaseStory2 = $tester->story->getZeroCase(2);
$zeroCaseStory3 = $tester->story->getZeroCase(0);

r(count($zeroCaseStory1)) && p() && e('1'); // 获取产品1下的零用例需求数量
r(count($zeroCaseStory2)) && p() && e('1'); // 获取产品2下的零用例需求数量
r(count($zeroCaseStory3)) && p() && e('0'); // 获取产品0下的零用例需求数量
r($zeroCaseStory1)        && p('4:title,status,type,stage') && e('软件需求4,active,story,planned');    // 获取产品1下的零用例需求详情
r($zeroCaseStory2)        && p('8:title,status,type,stage') && e('软件需求8,active,story,developing'); // 获取产品2下的零用例需求详情