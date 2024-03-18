#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getTeamMembers();
cid=0

- 查找需求20的相关团队成员数量 @1
- 查找需求21的变更时影响的团队成员数量 @1
- 查找需求21的变更时影响的团队成员数量属性user97 @user97
- 查找需求0的相关团队成员数量 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

zdTable('task')->gen(20);
$project = zdTable('project');
$project->status->range('doing');
$project->gen(20);
zdTable('projectstory')->gen(20);
zdTable('team')->gen(100);

global $tester;
$tester->loadModel('story');
$story20Members = $tester->story->getTeamMembers(20, '');
$story21Members = $tester->story->getTeamMembers(21, 'changed');
$story0Members  = $tester->story->getTeamMembers(0, '');

r(count($story20Members)) && p()         && e('1');      // 查找需求20的相关团队成员数量
r(count($story21Members)) && p()         && e('1');      // 查找需求21的变更时影响的团队成员数量
r($story21Members)        && p('user97') && e('user97'); // 查找需求21的变更时影响的团队成员数量
r(count($story0Members))  && p()         && e('0');      // 查找需求0的相关团队成员数量
