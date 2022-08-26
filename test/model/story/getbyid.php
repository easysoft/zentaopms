#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->getById();
cid=1
pid=1

 >> 6
获取ID为1、版本号为1的需求的名称 >> 用户需求版本一1
获取ID为1、版本号为2的需求的名称 >> 用户需求版本二21
获取ID为2、版本号为3的需求的名称 >> 用户需求版本三43
获取ID为20、版本号为2的需求的名称 >> 这是一个软件需求描述40

*/

$story = new storyTest();
$story1Version1  = $story->getByIdTest(1, 1);
$story1Version2  = $story->getByIdTest(1, 2);
$story2Version3  = $story->getByIdTest(3, 3);
$story20Version2 = $story->getByIdTest(20, 2);

r(count($story1Version1->tasks))  && p() && e('6');
r($story1Version1)  && p('title') && e('用户需求版本一1');        //获取ID为1、版本号为1的需求的名称
r($story1Version2)  && p('title') && e('用户需求版本二21');       //获取ID为1、版本号为2的需求的名称
r($story2Version3)  && p('title') && e('用户需求版本三43');       //获取ID为2、版本号为3的需求的名称
r($story20Version2) && p('spec')  && e('这是一个软件需求描述40'); //获取ID为20、版本号为2的需求的名称