#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getKanbanGroupData();
cid=0

- 传入stories数据，获取按照stage分组后的结果数量 @2
- 传入stories数据，获取按照stage分组后的结果数量 @2
- 传入stories数据，获取按照stage分组后的结果详情
 - 第2条的title属性 @软件需求2
 - 第2条的type属性 @story
 - 第2条的stage属性 @wait
- 传入stories数据，获取按照stage分组后的结果详情
 - 第6条的title属性 @软件需求6
 - 第6条的type属性 @story
 - 第6条的stage属性 @projected

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('story')->gen(10);

global $tester;
$tester->loadModel('story');
$stories1 = $tester->story->getProductStories(1);
$stories2 = $tester->story->getProductStories(2);

$storyGroup1 = $tester->story->getKanbanGroupData($stories1);
$storyGroup2 = $tester->story->getKanbanGroupData($stories2);

r(count($storyGroup1))        && p()                     && e('2');                          //传入stories数据，获取按照stage分组后的结果数量
r(count($storyGroup2))        && p()                     && e('2');                          //传入stories数据，获取按照stage分组后的结果数量
r($storyGroup1['wait'])       && p('2:title,type,stage') && e('软件需求2,story,wait');       //传入stories数据，获取按照stage分组后的结果详情
r($storyGroup2['projected'])  && p('6:title,type,stage') && e('软件需求6,story,projected');  //传入stories数据，获取按照stage分组后的结果详情
