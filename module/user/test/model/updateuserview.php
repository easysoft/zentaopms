#!/usr/bin/env php
<?php
/**

title=测试 userModel->updateUserView();
cid=1
pid=1

- 测试更新不存在对象类型的用户视图。 @0
- 测试更新不存在的项目集的用户视图。 @0
- 测试更新不存在的产品的用户视图。 @0
- 测试更新不存在的项目的用户视图。 @0
- 测试更新不存在的迭代的用户视图。 @0
- 测试更新ID为2的项目集的用户视图。 @1
- 查看ID为2的项目集的用户视图是否更新成功属性programs @,2
- 测试更新ID为3的项目集的用户视图。 @1
- 查看ID为3的项目集的用户视图是否更新成功属性programs @,2,3
- 测试只更新user2用户ID为8的项目集的用户视图。 @1
- 查看ID为8的项目集的用户视图是否更新成功属性programs @,2,3,8
- 测试更新ID为12的产品的用户视图。 @1
- 查看ID为12的产品的用户视图是否更新成功属性products @,12
- 测试更新ID为13的产品的用户视图。 @1
- 查看ID为13的产品的用户视图是否更新成功属性products @,12,13
- 测试只更新user2用户ID为18的产品的用户视图。 @1
- 查看ID为18的产品的用户视图是否更新成功属性products @,12,13,18
- 测试更新ID为12的项目的用户视图。 @1
- 查看ID为12的项目的用户视图是否更新成功属性projects @,12
- 测试更新ID为14的项目的用户视图。 @1
- 查看ID为14的项目的用户视图是否更新成功属性projects @,12,14
- 测试只更新user2用户ID为18的项目的用户视图。 @1
- 查看ID为18的项目的用户视图是否更新成功属性projects @,12,14,18
- 测试更新ID为107的迭代的用户视图。 @1
- 查看ID为107的迭代的用户视图是否更新成功属性sprints @,107
- 测试更新ID为108的迭代的用户视图。 @1
- 查看ID为108的迭代的用户视图是否更新成功属性sprints @,107,108
- 测试只更新user2用户ID为114的迭代的用户视图。 @1
- 查看ID为114的迭代的用户视图是否更新成功属性sprints @,107,108,114

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

dao::$cache = array();
zdTable('user')->gen(10);
zdTable('group')->config('group')->gen(100);
zdTable('usergroup')->config('usergroup')->gen(100);
zdTable('grouppriv')->config('grouppriv')->gen(100);
zdTable('userview')->config('userview')->gen(10);
zdTable('team')->config('team')->gen(200);
zdTable('acl')->config('acl')->gen(100);
zdTable('stakeholder')->config('stakeholder')->gen(100);

zdTable('product')->gen(20);
zdTable('project')->gen(0);
zdTable('project')->gen(20);
zdTable('project')->config('execution')->gen(20, false);
zdTable('projectadmin')->config('projectadmin')->gen(30);

$tester->loadModel('user');
r($tester->user->updateUserView(array(), 'ddd',     array())) && p('') && e(0); // 测试更新不存在对象类型的用户视图。
r($tester->user->updateUserView(array(), 'program', array())) && p('') && e(0); // 测试更新不存在的项目集的用户视图。
r($tester->user->updateUserView(array(), 'product', array())) && p('') && e(0); // 测试更新不存在的产品的用户视图。
r($tester->user->updateUserView(array(), 'project', array())) && p('') && e(0); // 测试更新不存在的项目的用户视图。
r($tester->user->updateUserView(array(), 'sprint',  array())) && p('') && e(0); // 测试更新不存在的迭代的用户视图。

r($tester->user->updateUserView(array(2), 'program', array()))        && p('') && e(1);                                          // 测试更新ID为2的项目集的用户视图。
r($tester->dao->select('*')->from(TABLE_USERVIEW)->where('account')->eq('user2')->fetch()) && p('programs', '-') && e(',2');     // 查看ID为2的项目集的用户视图是否更新成功
r($tester->user->updateUserView(array(3), 'program', array()))        && p('') && e(1);                                          // 测试更新ID为3的项目集的用户视图。
r($tester->dao->select('*')->from(TABLE_USERVIEW)->where('account')->eq('user2')->fetch()) && p('programs', '-') && e(',2,3');   // 查看ID为3的项目集的用户视图是否更新成功
r($tester->user->updateUserView(array(8), 'program', array('user2'))) && p('') && e(1);                                          // 测试只更新user2用户ID为8的项目集的用户视图。
r($tester->dao->select('*')->from(TABLE_USERVIEW)->where('account')->eq('user2')->fetch()) && p('programs', '-') && e(',2,3,8'); // 查看ID为8的项目集的用户视图是否更新成功

r($tester->user->updateUserView(array(12), 'product', array()))        && p('') && e(1);                                            // 测试更新ID为12的产品的用户视图。
r($tester->dao->select('*')->from(TABLE_USERVIEW)->where('account')->eq('user2')->fetch()) && p('products', '-') && e(',12');       // 查看ID为12的产品的用户视图是否更新成功
r($tester->user->updateUserView(array(13), 'product', array()))        && p('') && e(1);                                            // 测试更新ID为13的产品的用户视图。
r($tester->dao->select('*')->from(TABLE_USERVIEW)->where('account')->eq('user2')->fetch()) && p('products', '-') && e(',12,13');    // 查看ID为13的产品的用户视图是否更新成功
r($tester->user->updateUserView(array(18), 'product', array('user2'))) && p('') && e(1);                                            // 测试只更新user2用户ID为18的产品的用户视图。
r($tester->dao->select('*')->from(TABLE_USERVIEW)->where('account')->eq('user2')->fetch()) && p('products', '-') && e(',12,13,18'); // 查看ID为18的产品的用户视图是否更新成功

r($tester->user->updateUserView(array(12), 'project', array()))        && p('') && e(1);                                            // 测试更新ID为12的项目的用户视图。
r($tester->dao->select('*')->from(TABLE_USERVIEW)->where('account')->eq('user2')->fetch()) && p('projects', '-') && e(',12');       // 查看ID为12的项目的用户视图是否更新成功
r($tester->user->updateUserView(array(14), 'project', array()))        && p('') && e(1);                                            // 测试更新ID为14的项目的用户视图。
r($tester->dao->select('*')->from(TABLE_USERVIEW)->where('account')->eq('user2')->fetch()) && p('projects', '-') && e(',12,14');    // 查看ID为14的项目的用户视图是否更新成功
r($tester->user->updateUserView(array(18), 'project', array('user2'))) && p('') && e(1);                                            // 测试只更新user2用户ID为18的项目的用户视图。
r($tester->dao->select('*')->from(TABLE_USERVIEW)->where('account')->eq('user2')->fetch()) && p('projects', '-') && e(',12,14,18'); // 查看ID为18的项目的用户视图是否更新成功

r($tester->user->updateUserView(array(107), 'sprint', array()))        && p('') && e(1);                                              // 测试更新ID为107的迭代的用户视图。
r($tester->dao->select('*')->from(TABLE_USERVIEW)->where('account')->eq('user2')->fetch()) && p('sprints', '-') && e(',107');         // 查看ID为107的迭代的用户视图是否更新成功
r($tester->user->updateUserView(array(108), 'sprint', array()))        && p('') && e(1);                                              // 测试更新ID为108的迭代的用户视图。
r($tester->dao->select('*')->from(TABLE_USERVIEW)->where('account')->eq('user2')->fetch()) && p('sprints', '-') && e(',107,108');     // 查看ID为108的迭代的用户视图是否更新成功
r($tester->user->updateUserView(array(114), 'sprint', array('user2'))) && p('') && e(1);                                              // 测试只更新user2用户ID为114的迭代的用户视图。
r($tester->dao->select('*')->from(TABLE_USERVIEW)->where('account')->eq('user2')->fetch()) && p('sprints', '-') && e(',107,108,114'); // 查看ID为114的迭代的用户视图是否更新成功
