#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->getByList();
cid=1
pid=1

根据指定的ID列表，获取需求数量 >> 5
获取软件需求数量 >> 10
获取用户需求数量 >> 10
根据指定的ID列表，获取第一个需求的名称 >> 用户需求1
获取第一个软件需求的名称 >> 软件需求2
获取第一个用户需求的名称 >> 用户需求1
传入空类型，返回空列表 >> 0

*/

$story = new storyTest();
$storyIdList = array(1, 2, 3, 4, 5);

$storyInIdList = $story->getByListTest($storyIdList);
$stories       = $story->getByListTest(0, 'story');
$reqiurements  = $story->getByListTest(0, 'requirement');
$emptyStories  = $story->getByListTest(0, '');

r(count($storyInIdList)) && p()          && e('5');          //根据指定的ID列表，获取需求数量
r(count($stories))       && p()          && e('10');         //获取软件需求数量
r(count($reqiurements))  && p()          && e('10');         //获取用户需求数量
r($storyInIdList)        && p('1:title') && e('用户需求1');  //根据指定的ID列表，获取第一个需求的名称
r($stories)              && p('2:title') && e('软件需求2');  //获取第一个软件需求的名称
r($reqiurements)         && p('1:title') && e('用户需求1');  //获取第一个用户需求的名称
r($emptyStories)         && p('')        && e('0');          //传入空类型，返回空列表