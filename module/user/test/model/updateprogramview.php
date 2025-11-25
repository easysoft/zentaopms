#!/usr/bin/env php
<?php

/**

title=测试 userModel->updateProgramView();
cid=19662

- 测试更新ID为2的项目集的用户视图。 @1
- 查看ID为2的项目集的用户视图是否更新成功属性programs @,2
- 测试更新ID为3的项目集的用户视图。 @1
- 查看ID为3的项目集的用户视图是否更新成功属性programs @,2,3
- 测试只更新user2用户ID为8的项目集的用户视图。 @1
- 查看ID为8的项目集的用户视图是否更新成功属性programs @,2,3,8

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('user')->gen(10);
zenData('group')->loadYaml('group')->gen(100);
zenData('usergroup')->loadYaml('usergroup')->gen(100);
zenData('grouppriv')->loadYaml('grouppriv')->gen(100);
zenData('userview')->loadYaml('userview')->gen(10);
zenData('team')->loadYaml('team')->gen(200);
zenData('acl')->loadYaml('acl')->gen(100);
zenData('stakeholder')->loadYaml('stakeholder')->gen(100);

zenData('product')->gen(20);
zenData('project')->gen(0);
zenData('project')->gen(20);
zenData('project')->loadYaml('execution')->gen(20, false);
zenData('projectadmin')->loadYaml('projectadmin')->gen(30);

$tester->loadModel('user');
r($tester->user->updateProgramView(array(2), array()))        && p('') && e(1);                                                  // 测试更新ID为2的项目集的用户视图。
r($tester->dao->select('*')->from(TABLE_USERVIEW)->where('account')->eq('user2')->fetch()) && p('programs', '-') && e(',2');     // 查看ID为2的项目集的用户视图是否更新成功
r($tester->user->updateProgramView(array(3), array()))        && p('') && e(1);                                                  // 测试更新ID为3的项目集的用户视图。
r($tester->dao->select('*')->from(TABLE_USERVIEW)->where('account')->eq('user2')->fetch()) && p('programs', '-') && e(',2,3');   // 查看ID为3的项目集的用户视图是否更新成功
r($tester->user->updateProgramView(array(8), array('user2'))) && p('') && e(1);                                                  // 测试只更新user2用户ID为8的项目集的用户视图。
r($tester->dao->select('*')->from(TABLE_USERVIEW)->where('account')->eq('user2')->fetch()) && p('programs', '-') && e(',2,3,8'); // 查看ID为8的项目集的用户视图是否更新成功
