#!/usr/bin/env php
<?php
/**

title=测试 customModel->processProjectAcl();
timeout=0
cid=15922

- 处理项目权限为继承项目集的项目权限
 - 属性id @11
 - 属性acl @private
 - 属性whitelist @admin,user4
- 处理项目权限为继承项目集的项目权限
 - 属性id @60
 - 属性acl @private
 - 属性whitelist @user1,user3
- 处理项目权限为继承项目集的项目权限
 - 属性id @61
 - 属性acl @program
 - 属性whitelist @,,
- 处理项目权限为继承项目集的项目权限
 - 属性id @100
 - 属性acl @program
 - 属性whitelist @,,
- 处理项目权限为继承项目集的项目权限
 - 属性id @1
 - 属性acl @open
 - 属性whitelist @,admin,

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$projectTable = zenData('project')->loadYaml('project');
$projectTable->acl->range('open{10},program{4}');
$projectTable->PM->range('admin,user1,user2,user3,user4');
$projectTable->gen(14);

$stakeholderTable = zenData('stakeholder');
$stakeholderTable->objectID->range('1-10');
$stakeholderTable->objectType->range('program');
$stakeholderTable->user->range('user4,user3,user2,user1,admin');
$stakeholderTable->gen(10);

zenData('user')->gen(5);
su('admin');

$projects = array(11, 60, 61, 100, 1);

$customTester = new customModelTest();
r($customTester->processProjectAclTest($projects[0])) && p('id;acl;whitelist', ';') && e('11;private;admin,user4'); // 处理项目权限为继承项目集的项目权限
r($customTester->processProjectAclTest($projects[1])) && p('id;acl;whitelist', ';') && e('60;private;user1,user3'); // 处理项目权限为继承项目集的项目权限
r($customTester->processProjectAclTest($projects[2])) && p('id;acl;whitelist', ';') && e('61;program;,,');          // 处理项目权限为继承项目集的项目权限
r($customTester->processProjectAclTest($projects[3])) && p('id;acl;whitelist', ';') && e('100;program;,,');         // 处理项目权限为继承项目集的项目权限
r($customTester->processProjectAclTest($projects[4])) && p('id;acl;whitelist', ';') && e('1;open;,admin,');         // 处理项目权限为继承项目集的项目权限
