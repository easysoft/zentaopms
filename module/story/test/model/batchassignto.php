#!/usr/bin/env php
<?php

/**

title=测试 storyModel->batchAssignTo();
cid=18464

- 批量指派6个需求，查看修改成功的需求数量 @5
- 批量指派6个需求，查看修改成功的需求指派人第1条的assignedTo属性 @test20
- 批量指派6个需求，查看修改成功的需求指派人第2条的assignedTo属性 @test20
- 批量指派6个需求，查看修改成功的需求指派人第4条的assignedTo属性 @test20
- 批量指派6个需求，查看修改成功的需求指派人第5条的assignedTo属性 @test20
- 批量指派6个需求，查看修改成功的需求指派人第6条的assignedTo属性 @test20

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$story = zenData('story');
$story->version->range(1);
$story->assignedTo->range('``');
$story->gen(20);
zenData('storyspec')->gen(20);
zenData('user')->gen(20);
zenData('product')->gen(30);

$story = new storyModelTest();

$params['assignedTo']  = 'test20';
$params['storyIdList'] = array(1, 2, 3, 4, 5, 6);

$stories = $story->batchAssignToTest($params['storyIdList'], $params['assignedTo']);

r(count($stories)) && p()               && e('5');      // 批量指派6个需求，查看修改成功的需求数量
r($stories)        && p('1:assignedTo') && e('test20'); // 批量指派6个需求，查看修改成功的需求指派人
r($stories)        && p('2:assignedTo') && e('test20'); // 批量指派6个需求，查看修改成功的需求指派人
r($stories)        && p('4:assignedTo') && e('test20'); // 批量指派6个需求，查看修改成功的需求指派人
r($stories)        && p('5:assignedTo') && e('test20'); // 批量指派6个需求，查看修改成功的需求指派人
r($stories)        && p('6:assignedTo') && e('test20'); // 批量指派6个需求，查看修改成功的需求指派人
