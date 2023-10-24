#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->batchChangeBranch();
cid=1
pid=1

批量修改需求的所属分支，判断被修改分支需求的数量 >> 3
批量修改需求的所属分支，判断需求2修改后的分支ID >> 2
批量修改需求的所属分支，判断需求4修改后的分支ID >> 2
批量修改需求的所属分支，判断需求6修改后的分支ID >> 2

*/

$story = new storyTest();
$storyIdList = array(2, 4, 6);

$stories = $story->batchChangeBranchTest($storyIdList, 2);

r(count($stories)) && p()           && e('3'); // 批量修改需求的所属分支，判断被修改分支需求的数量
r($stories)        && p('2:branch') && e('2'); // 批量修改需求的所属分支，判断需求2修改后的分支ID
r($stories)        && p('4:branch') && e('2'); // 批量修改需求的所属分支，判断需求4修改后的分支ID
r($stories)        && p('6:branch') && e('2'); // 批量修改需求的所属分支，判断需求6修改后的分支ID
