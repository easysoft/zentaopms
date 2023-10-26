#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('project')->gen(20);
$team = zdTable('team');
$team->root->range('11');
$team->gen(10);

/**

title=测试 projectTao::deleteMembers();
timeout=0
cid=1

*/

global $tester;
$tester->loadModel('project');

$oldTeams = $tester->project->dao->select('*')->from(TABLE_TEAM)->where('root')->eq(11)->fetchAll('account');
$members = array('admin', 'user3', 'user4', 'user5');
$tester->project->deleteMembers(11, 'admin', $members);
$newTeams = $tester->project->dao->select('*')->from(TABLE_TEAM)->where('root')->eq(11)->fetchAll('account');

r(count($oldTeams)) && p() && e('10');
r(count($newTeams)) && p() && e('7');
