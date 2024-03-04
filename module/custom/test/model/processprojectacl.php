#!/usr/bin/env php
<?php
/**

title=测试 customModel->processProjectAcl();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/custom.class.php';

$projectTable = zdTable('project')->config('project');
$projectTable->acl->range('open{10},program{4}');
$projectTable->PM->range('admin,user1,user2,user3,user4');
$projectTable->gen(14);

$stakeholderTable = zdTable('stakeholder');
$stakeholderTable->objectID->range('1-10');
$stakeholderTable->objectType->range('program');
$stakeholderTable->user->range('user4,user3,user2,user1,admin');
$stakeholderTable->gen(10);

zdTable('user')->gen(5);
su('admin');

$projects = array(11, 60);

$customTester = new customTest();
r($customTester->processProjectAclTest($projects[0])) && p('id;acl;whitelist', ';') && e('11;private;admin,user4'); // 处理项目权限为继承项目集的项目权限
r($customTester->processProjectAclTest($projects[1])) && p('id;acl;whitelist', ';') && e('60;private;user1,user3'); // 处理项目权限为继承项目集的项目权限
