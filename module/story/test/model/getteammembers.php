#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

zdTable('task')->gen(20);
$project = zdTable('project');
$project->status->range('doing');
$project->gen(20);
zdTable('projectstory')->gen(20);
zdTable('team')->gen(100);

/**

title=测试 storyModel->getTeamMembers();
cid=1
pid=1

查找需求20的相关团队成员数量 >> 2
查找需求21的变更时影响的团队成员数量 >> 3
查找需求21的变更时影响的团队成员数量 >> po87,user97,test97
查找需求0的相关团队成员数量 >> 0

*/

global $tester;
$tester->loadModel('story');
$story20Members = $tester->story->getTeamMembers(20, '');
$story21Members = $tester->story->getTeamMembers(21, 'changed');
$story0Members  = $tester->story->getTeamMembers(0, '');

r(count($story20Members)) && p()         && e('1');      // 查找需求20的相关团队成员数量
r(count($story21Members)) && p()         && e('1');      // 查找需求21的变更时影响的团队成员数量
r($story21Members)        && p('user97') && e('user97'); // 查找需求21的变更时影响的团队成员数量
r(count($story0Members))  && p()         && e('0');      // 查找需求0的相关团队成员数量
