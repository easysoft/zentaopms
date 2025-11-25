#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getKanbanGroupData();
timeout=0
cid=18539

- 查看获取到的第一个需求看板分组数据数量 @3
- 查看获取到的第二个需求看板分组数据数量 @3
- 查看获取到的第一个需求看板分组数据的第一个列的名称
 - 第0条的name属性 @1
 - 第0条的title属性 @研发立项 (2)
- 查看获取到的第二个需求看板分组数据的第一个泳道的名称
 - 第0条的name属性 @1
 - 第0条的title属性 @~~
- 查看获取到的第二个需求看板分组数据的所有Items数量 @5

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('story')->gen(10);

global $tester;
$tester->loadModel('story');
$stories1 = $tester->story->getProductStories(1);
$stories2 = $tester->story->getProductStories(2);

$storyGroup1 = $tester->story->getKanbanGroupData($stories1);
$storyGroup2 = $tester->story->getKanbanGroupData($stories2);

r(count($storyGroup1))    && p()               && e('3');              // 查看获取到的第一个需求看板分组数据数量
r(count($storyGroup2))    && p()               && e('3');              // 查看获取到的第二个需求看板分组数据数量
r($storyGroup1['cols'])   && p('0:name,title') && e('1,研发立项 (2)'); // 查看获取到的第一个需求看板分组数据的第一个列的名称
r($storyGroup2['lanes'])  && p('0:name,title') && e('1,~~');           // 查看获取到的第二个需求看板分组数据的第一个泳道的名称
r(count($storyGroup2['items'], true)) && p()   && e('5');              // 查看获取到的第二个需求看板分组数据的所有Items数量