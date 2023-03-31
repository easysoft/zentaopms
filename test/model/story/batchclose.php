#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->batchClose();
cid=1
pid=1

批量关闭6个需求，查看被关闭的需求数量 >> 6
批量关闭需求，查看需求1被关闭后的字段 >> closed,closed,done,0
批量关闭需求，查看需求2被关闭后的字段 >> closed,closed,willnotdo,0
批量关闭需求，查看需求3被关闭后的字段 >> closed,closed,putoff,0
批量关闭需求，查看需求4被关闭后的字段 >> closed,closed,duplicate,8
批量关闭需求，查看需求5被关闭后的字段 >> closed,closed,cancel,0
批量关闭需求，查看需求6被关闭后的字段 >> closed,closed,bydesign,0

*/

$story = new storyTest();
$storyIdList        = array(1, 2, 3, 4, 5, 6);
$closedReasons      = array(1 => 'done', 2 => 'willnotdo', 3 => 'putoff', 4 => 'duplicate', 5 => 'cancel', 6 => 'bydesign');
$duplicateList      = array(1 => '', 2 => '', 3 => '', 4 => 8,  5 => '', 6 => '');
$childStoriesIDList = array(1 => '', 2 => '', 3 => '', 4 => '', 5 => '', 6 => '');

$params['storyIdList']          = $storyIdList;
$params['closedReasons']        = $closedReasons;
$params['duplicateStoryIDList'] = $duplicateList;
$params['childStoriesIDList']   = $childStoriesIDList;
$stories = $story->batchCloseTest($params);

r(count($stories)) && p()                              && e('6'); // 批量关闭6个需求，查看被关闭的需求数量
r($stories)        && p('1:status,stage,closedReason,duplicateStory') && e('closed,closed,done,0');      // 批量关闭需求，查看需求1被关闭后的字段
r($stories)        && p('2:status,stage,closedReason,duplicateStory') && e('closed,closed,willnotdo,0'); // 批量关闭需求，查看需求2被关闭后的字段
r($stories)        && p('3:status,stage,closedReason,duplicateStory') && e('closed,closed,putoff,0');    // 批量关闭需求，查看需求3被关闭后的字段
r($stories)        && p('4:status,stage,closedReason,duplicateStory') && e('closed,closed,duplicate,8'); // 批量关闭需求，查看需求4被关闭后的字段
r($stories)        && p('5:status,stage,closedReason,duplicateStory') && e('closed,closed,cancel,0');    // 批量关闭需求，查看需求5被关闭后的字段
r($stories)        && p('6:status,stage,closedReason,duplicateStory') && e('closed,closed,bydesign,0');  // 批量关闭需求，查看需求6被关闭后的字段
