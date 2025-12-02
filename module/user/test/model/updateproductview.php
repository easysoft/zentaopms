#!/usr/bin/env php
<?php

/**

title=测试 userModel->updateProductView();
cid=19661

- 测试更新ID为12的产品的用户视图。 @1
- 查看ID为12的产品的用户视图是否更新成功属性products @,12
- 测试更新ID为13的产品的用户视图。 @1
- 查看ID为13的产品的用户视图是否更新成功属性products @,12,13
- 测试只更新user2用户ID为18的产品的用户视图。 @1
- 查看ID为18的产品的用户视图是否更新成功属性products @,12,13,18

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
r($tester->user->updateProductView(array(12), array()))        && p('') && e(1);                                                    // 测试更新ID为12的产品的用户视图。
r($tester->dao->select('*')->from(TABLE_USERVIEW)->where('account')->eq('user2')->fetch()) && p('products', '-') && e(',12');       // 查看ID为12的产品的用户视图是否更新成功
r($tester->user->updateProductView(array(13), array()))        && p('') && e(1);                                                    // 测试更新ID为13的产品的用户视图。
r($tester->dao->select('*')->from(TABLE_USERVIEW)->where('account')->eq('user2')->fetch()) && p('products', '-') && e(',12,13');    // 查看ID为13的产品的用户视图是否更新成功
r($tester->user->updateProductView(array(18), array('user2'))) && p('') && e(1);                                                    // 测试只更新user2用户ID为18的产品的用户视图。
r($tester->dao->select('*')->from(TABLE_USERVIEW)->where('account')->eq('user2')->fetch()) && p('products', '-') && e(',12,13,18'); // 查看ID为18的产品的用户视图是否更新成功
