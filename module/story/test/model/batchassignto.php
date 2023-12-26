#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

$story = zdTable('story');
$story->version->range(1);
$story->assignedTo->range('``');
$story->gen(20);
zdTable('storyspec')->gen(20);
zdTable('user')->gen(20);
zdTable('product')->gen(30);

/**

title=测试 storyModel->batchAssignTo();
cid=1
pid=1

*/

$story = new storyTest();

$params['assignedTo']  = 'test20';
$params['storyIdList'] = array(1, 2, 3, 4, 5, 6);

$stories = $story->batchAssignToTest($params['storyIdList'], $params['assignedTo']);

r(count($stories)) && p()               && e('5');      // 批量指派6个需求，查看修改成功的需求数量
r($stories)        && p('1:assignedTo') && e('test20'); // 批量指派6个需求，查看修改成功的需求指派人
r($stories)        && p('2:assignedTo') && e('test20'); // 批量指派6个需求，查看修改成功的需求指派人
r($stories)        && p('4:assignedTo') && e('test20'); // 批量指派6个需求，查看修改成功的需求指派人
r($stories)        && p('5:assignedTo') && e('test20'); // 批量指派6个需求，查看修改成功的需求指派人
r($stories)        && p('6:assignedTo') && e('test20'); // 批量指派6个需求，查看修改成功的需求指派人

