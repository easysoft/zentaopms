#!/usr/bin/env php
<?php

/**

title=测试 customModel->getDataForUpdateProjectAcl();
timeout=0
cid=15930

- 获取项目权限为继承项目集的项目数据的信息。
 - 属性id @11
 - 属性parent @1
- 获取项目权限为继承项目集的项目集负责人的信息。
 - 属性1 @admin
 - 属性2 @user1
- 获取项目权限为继承项目集的项目集干系人的信息。
 - 属性objectID @1
 - 属性objectType @program
 - 属性user @user4
- 获取项目权限为继承项目集的项目数据的数量。 @2
- 获取项目权限为继承项目集的项目集负责人的数量。 @2
- 获取项目权限为继承项目集的项目集干系人的数量。 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/custom.unittest.class.php';

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

$customTester = new customTest();
$projectGroup = $customTester->getDataForUpdateProjectAclTest('projectGroup');
$programPM    = $customTester->getDataForUpdateProjectAclTest('programPM');
$stakeholders = $customTester->getDataForUpdateProjectAclTest('stakeholders');

r($projectGroup[1][11])      && p('id,parent')                && e('11,1');            // 获取项目权限为继承项目集的项目数据的信息。
r($programPM)                && p('1,2')                      && e('admin,user1');     // 获取项目权限为继承项目集的项目集负责人的信息。
r($stakeholders[1]['user4']) && p('objectID,objectType,user') && e('1,program,user4'); // 获取项目权限为继承项目集的项目集干系人的信息。

r(count($projectGroup)) && p() && e('2'); // 获取项目权限为继承项目集的项目数据的数量。
r(count($programPM))    && p() && e('2'); // 获取项目权限为继承项目集的项目集负责人的数量。
r(count($stakeholders)) && p() && e('2'); // 获取项目权限为继承项目集的项目集干系人的数量。