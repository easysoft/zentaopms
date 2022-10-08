#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->getStorySpecs();
cid=1
pid=1

根据传入的需求ID列表，获取最新版本的需求描述信息 >> 这是一个软件需求描述41
根据传入的需求ID列表，获取最新版本的验收标准信息 >> 这是一个需求验收55
传入三个需求ID，判断获取到的需求描述信息数量 >> 2

*/

$story = new storyTest();
$storyIdList = array(1, 15, 100);

r($story->getStorySpecsTest($storyIdList))        && p('1:spec')     && e('这是一个软件需求描述41'); //根据传入的需求ID列表，获取最新版本的需求描述信息
r($story->getStorySpecsTest($storyIdList))        && p('15:verify')  && e('这是一个需求验收55');     //根据传入的需求ID列表，获取最新版本的验收标准信息
r(count($story->getStorySpecsTest($storyIdList))) && p()             && e('2');                      //传入三个需求ID，判断获取到的需求描述信息数量