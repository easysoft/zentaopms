#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('project')->gen(20);
$team = zenData('team');
$team->root->range('11');
$team->gen(10);

/**

title=测试 projectTao::deleteMembers();
timeout=0
cid=17896

- 检查原来的成员数 @10
- 检查删除后的成员数 @7
- 检查创建者是否存在 @1
- 创建者不会被删除 @1
- 检查 user3 的成员是否存在 @1
- 检查删除的成员是否存在 @0

*/

global $tester;
$tester->loadModel('project');

$oldTeams = $tester->project->dao->select('*')->from(TABLE_TEAM)->where('root')->eq(11)->fetchAll('account');
$members = array('admin', 'user3', 'user4', 'user5');
$tester->project->deleteMembers(11, 'admin', $members);
$newTeams = $tester->project->dao->select('*')->from(TABLE_TEAM)->where('root')->eq(11)->fetchAll('account');

r(count($oldTeams))               && p() && e('10'); // 检查原来的成员数
r(count($newTeams))               && p() && e('7');  // 检查删除后的成员数
r((int)isset($oldTeams['admin'])) && p() && e('1');  // 检查创建者是否存在
r((int)isset($newTeams['admin'])) && p() && e('1');  // 创建者不会被删除
r((int)isset($oldTeams['user3'])) && p() && e('1');  // 检查 user3 的成员是否存在
r((int)isset($newTeams['user3'])) && p() && e('0');  // 检查删除的成员是否存在
