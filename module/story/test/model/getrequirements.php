#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getRequirements();
cid=0

- 获取产品1下的第一个用户需求名称属性5 @用户需求5
- 获取产品1下的所有用户需求数量 @1
- 获取产品5下的第一个用户需求名称属性17 @用户需求17
- 获取产品5下的所有用户需求数量 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

$story = zdTable('story');
$story->status->range('draft,active,closed');
$story->gen(20);

$story = new storyTest();
$requirements1 = $story->getRequirementsTest(2);
$requirements2 = $story->getRequirementsTest(5);

r($requirements1)        && p('5')  && e('用户需求5');  //获取产品1下的第一个用户需求名称
r(count($requirements1)) && p()     && e('1');          //获取产品1下的所有用户需求数量
r($requirements2)        && p('17') && e('用户需求17'); //获取产品5下的第一个用户需求名称
r(count($requirements2)) && p()     && e('1');          //获取产品5下的所有用户需求数量
