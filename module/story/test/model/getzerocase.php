#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('story')->gen(10);
zdTable('case')->gen(10);

/**

title=测试 storyModel->getZeroCase();
cid=1
pid=1

获取产品1下的零用例需求数量 >> 1
获取产品0下的零用例需求数量 >> 0
获取产品1下的零用例需求详情 >> 软件需求4,changing,story,planned

*/

global $tester;
$tester->loadModel('story');
$zeroCaseStory1 = $tester->story->getZeroCase(1);
$zeroCaseStory3 = $tester->story->getZeroCase(0);

r(count($zeroCaseStory1)) && p() && e('1'); // 获取产品1下的零用例需求数量
r(count($zeroCaseStory3)) && p() && e('0'); // 获取产品0下的零用例需求数量
r($zeroCaseStory1)        && p('4:title,status,type,stage') && e('软件需求4,changing,story,planned');    // 获取产品1下的零用例需求详情
