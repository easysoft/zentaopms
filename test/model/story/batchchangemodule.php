#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->batchChangeModule();
cid=1
pid=1

批量修改6个需求的模块，查看被修改模块的需求数量 >> 6
批量修改6个需求的模块，查看需求1修改后的模块ID >> 1366
批量修改6个需求的模块，查看需求2修改后的模块ID >> 1366
批量修改6个需求的模块，查看需求3修改后的模块ID >> 1366
批量修改6个需求的模块，查看需求4修改后的模块ID >> 1366
批量修改6个需求的模块，查看需求5修改后的模块ID >> 1366
批量修改6个需求的模块，查看需求6修改后的模块ID >> 1366

*/

$story       = new storyTest();
$storyIdList = array(1, 2, 3, 4, 5, 6);
$stories     = $story->batchChangeModuleTest($storyIdList, 1366);

r(count($stories)) && p()           && e('6');    // 批量修改6个需求的模块，查看被修改模块的需求数量
r($stories)        && p('1:module') && e('1366'); // 批量修改6个需求的模块，查看需求1修改后的模块ID
r($stories)        && p('2:module') && e('1366'); // 批量修改6个需求的模块，查看需求2修改后的模块ID
r($stories)        && p('3:module') && e('1366'); // 批量修改6个需求的模块，查看需求3修改后的模块ID
r($stories)        && p('4:module') && e('1366'); // 批量修改6个需求的模块，查看需求4修改后的模块ID
r($stories)        && p('5:module') && e('1366'); // 批量修改6个需求的模块，查看需求5修改后的模块ID
r($stories)        && p('6:module') && e('1366'); // 批量修改6个需求的模块，查看需求6修改后的模块ID
