#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->batchAssignTo();
cid=1
pid=1

批量指派6个需求，查看修改成功的需求数量 >> 6
批量指派6个需求，查看修改成功的需求指派人 >> test20
批量指派6个需求，查看修改成功的需求指派人 >> test20
批量指派6个需求，查看修改成功的需求指派人 >> test20
批量指派6个需求，查看修改成功的需求指派人 >> test20
批量指派6个需求，查看修改成功的需求指派人 >> test20
批量指派6个需求，查看修改成功的需求指派人 >> test20

*/

$story = new storyTest();

$params['assignedTo']  = 'test20';
$params['storyIdList'] = array(1, 2, 3, 4, 5, 6);

$stories = $story->batchAssignToTest($params);

r(count($stories)) && p()               && e('6');      // 批量指派6个需求，查看修改成功的需求数量
r($stories)        && p('1:assignedTo') && e('test20'); // 批量指派6个需求，查看修改成功的需求指派人
r($stories)        && p('2:assignedTo') && e('test20'); // 批量指派6个需求，查看修改成功的需求指派人
r($stories)        && p('3:assignedTo') && e('test20'); // 批量指派6个需求，查看修改成功的需求指派人
r($stories)        && p('4:assignedTo') && e('test20'); // 批量指派6个需求，查看修改成功的需求指派人
r($stories)        && p('5:assignedTo') && e('test20'); // 批量指派6个需求，查看修改成功的需求指派人
r($stories)        && p('6:assignedTo') && e('test20'); // 批量指派6个需求，查看修改成功的需求指派人

