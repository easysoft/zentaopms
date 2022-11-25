#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->getRequierements();
cid=1
pid=1

获取产品1下的第一个用户需求名称 >> 用户需求1
获取产品1下的所有用户需求数量 >> 2
获取产品5下的第一个用户需求名称 >> 用户需求17
获取产品5下的所有用户需求数量 >> 2
获取产品ID为Null的用户需求数量 >> 0

*/

$story = new storyTest();
$requirements1 = $story->getRequierementsTest(1);
$requirements2 = $story->getRequierementsTest(5);
$requirements3 = $story->getRequierementsTest(null);

r($requirements1)        && p('1')  && e('用户需求1');  //获取产品1下的第一个用户需求名称
r(count($requirements1)) && p()     && e('2');          //获取产品1下的所有用户需求数量
r($requirements2)        && p('17') && e('用户需求17'); //获取产品5下的第一个用户需求名称
r(count($requirements2)) && p()     && e('2');          //获取产品5下的所有用户需求数量
r(count($requirements3)) && p()     && e('0');          //获取产品ID为Null的用户需求数量