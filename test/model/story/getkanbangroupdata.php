#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 storyModel->getKanbanGroupData();
cid=1
pid=1

传入stories数据，获取按照stage分组后的结果数量 >> 2
传入stories数据，获取按照stage分组后的结果数量 >> 2
传入stories数据，获取按照stage分组后的结果详情 >> 软件需求2,story,wait
传入stories数据，获取按照stage分组后的结果详情 >> 软件需求6,story,projected

*/

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