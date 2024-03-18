#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getStorySpecs();
cid=0

- 根据传入的需求ID列表，获取最新版本的需求描述信息第1条的spec属性 @这是一个软件需求描述1
- 根据传入的需求ID列表，获取最新版本的验收标准信息第15条的verify属性 @这是一个需求验收15
- 传入三个需求ID，判断获取到的需求描述信息数量 @2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

zdTable('storyspec')->gen(50);

$story = new storyTest();
$storyIdList = array(1, 15, 100);

$storyspecs = $story->getStorySpecsTest($storyIdList);

r($storyspecs)        && p('1:spec')     && e('这是一个软件需求描述1'); //根据传入的需求ID列表，获取最新版本的需求描述信息
r($storyspecs)        && p('15:verify')  && e('这是一个需求验收15');    //根据传入的需求ID列表，获取最新版本的验收标准信息
r(count($storyspecs)) && p()             && e('2');                     //传入三个需求ID，判断获取到的需求描述信息数量
