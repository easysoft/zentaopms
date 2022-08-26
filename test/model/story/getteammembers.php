#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

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

r(count($story20Members)) && p()                     && e('2');                  // 查找需求20的相关团队成员数量
r(count($story21Members)) && p()                     && e('3');                  // 查找需求21的变更时影响的团队成员数量
r($story21Members)        && p('po87,user97,test97') && e('po87,user97,test97'); // 查找需求21的变更时影响的团队成员数量
r(count($story0Members))  && p()                     && e('0');                  // 查找需求0的相关团队成员数量