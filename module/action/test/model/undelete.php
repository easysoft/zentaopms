#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';
su('admin');

zdTable('action')->config('action')->gen(25);
zdTable('product')->config('product')->gen(2);
zdTable('project')->config('execution')->gen(111);
zdTable('repo')->config('repo')->gen(3);
zdTable('pipeline')->gen(1);
zdTable('projectproduct')->config('projectproduct')->gen(1);
zdTable('module')->config('module')->gen(3);
zdTable('review')->config('review')->gen(2);
zdTable('release')->config('release')->gen(2);
zdTable('build')->config('build')->gen(1);
zdTable('case')->config('case')->gen(2);
zdTable('scene')->config('scene')->gen(3);
zdTable('doc')->config('doc')->gen(1);
zdTable('doccontent')->config('doccontent')->gen(1);
zdTable('file')->gen(1);
zdTable('doclib')->config('doclib')->gen(2);
zdTable('productplan')->config('productplan')->gen(2);
zdTable('task')->config('task')->gen(2);

/**

title=测试 actionModel->undelete();
timeout=0
cid=1

- 测试还原action 0, objectType execution 的数据。 @0
- 测试还原action 1, objectType execution 的数据。 @该数据在版本升级过程中未参与数据归并流程，不支持还原。
- 测试还原action 2, objectType execution 的数据。 @该执行没有所属的项目，请先还原项目再还原执行
- 测试还原action 3, objectType execution 的数据,并且测试是否恢复了用户访问权限。 @1
- 测试还原action 3, objectType execution 的数据,并且测试是否恢复了用户访问权限。 @1
- 测试还原action 5, objectType repo 的数据。 @该代码库没有所属的服务器，请先还原服务器再还原代码库
- 测试还原action 6, objectType repo 的数据。 @1
- 测试还原action 7, objectType repo 的数据。 @1
- 测试还原action 8, objectType program 的数据,并且测试是否恢复了用户访问权限。 @0
- 测试还原action 9, objectType program 的数据,并且测试是否恢复了用户访问权限。 @1
- 测试还原action 10, objectType project 的数据,并且测试是否恢复了用户访问权限。 @0
- 测试还原action 11, objectType project 的数据,并且测试是否恢复了用户访问权限。 @1
- 测试还原action 12, objectTyp prodcut 的数据,并且测试是否恢复了用户访问权限。 @1
- 测试还原action 13, objectType module数据。 @模块名“模块”已经存在！
- 测试还原action 14, objectType module 的数据。 @1
- 测试还原action 17, objectType release 的数据。 @1
- 测试还原action 18, objectType release 的数据。 @1
- 测试还原action 19, objectType case 的数据。 @还原用例之前，请先还原该用例所属场景
- 测试还原action 20, objectType case 的数据。 @1
- 测试还原action 21, objectType scene 的数据。 @还原场景之前，请先还原该场景的父场景
- 测试还原action 22, objectType scene 的数据。 @1
- 测试还原action 23, objectType doc 的数据。 @1
- 测试还原action 24, objectType productplan 的数据。 @1
- 测试还原action 25, objectType task 的数据。 @1

*/

$actionIDList = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25);

$action = new actionTest();

r($action->undeleteTest($actionIDList[0])) && p('') && e('0');                                                                           // 测试还原action 0, objectType execution 的数据。
r($action->undeleteTest($actionIDList[1])) && p('') && e('该数据在版本升级过程中未参与数据归并流程，不支持还原。');                      // 测试还原action 1, objectType execution 的数据。
r($action->undeleteTest($actionIDList[2])) && p('') && e('该执行没有所属的项目，请先还原项目再还原执行');                                // 测试还原action 2, objectType execution 的数据。

$tester->dao->update('zt_userview')->set('sprints')->eq('')->set('programs')->eq('')->set('products')->eq('')->set('projects')->eq('')->where('account')->eq('admin')->exec();

$result   = $action->undeleteTest($actionIDList[3]);
$userView = $tester->dao->select('*')->from('zt_userview')->where('account')->eq('admin')->fetch();
$doclib  = $tester->dao->select('*')->from('zt_doclib')->where('type')->eq('execution')->andWhere('execution')->eq('4')->fetch();
r($result && strpos($userView->products . ',', ',1,') !== false && !$doclib->deleted) && p('') && e('1');                                 // 测试还原action 3, objectType execution 的数据,并且测试是否恢复了用户访问权限。

$result   = $action->undeleteTest($actionIDList[4]);
unset(dao::$cache['zt_userview']);
$userView = $tester->dao->select('*')->from('zt_userview')->where('account')->eq('admin')->fetch();
r($result && strpos($userView->products . ',', ',1,') !== false && strpos($userView->sprints . ',', ',4,') !== false) && p('') && e('1'); // 测试还原action 3, objectType execution 的数据,并且测试是否恢复了用户访问权限。

r($action->undeleteTest($actionIDList[5])) && p('') && e('该代码库没有所属的服务器，请先还原服务器再还原代码库');                         // 测试还原action 5, objectType repo 的数据。
r($action->undeleteTest($actionIDList[6])) && p('') && e('1');                                                                            // 测试还原action 6, objectType repo 的数据。
r($action->undeleteTest($actionIDList[7])) && p('') && e('1');                                                                            // 测试还原action 7, objectType repo 的数据。

$tester->dao->update('zt_userview')->set('sprints')->eq('')->set('programs')->eq('')->set('products')->eq('')->set('projects')->eq('')->where('account')->eq('admin')->exec();

$result   = $action->undeleteTest($actionIDList[8]);
unset(dao::$cache['zt_userview']);
$userView = $tester->dao->select('*')->from('zt_userview')->where('account')->eq('admin')->fetch();
r($result && strpos($userView->products . ',', ',1,') !== false) && p('') && e('0');                                                      // 测试还原action 8, objectType program 的数据,并且测试是否恢复了用户访问权限。

$tester->dao->update('zt_userview')->set('sprints')->eq('')->set('programs')->eq('')->set('products')->eq('')->set('projects')->eq('')->where('account')->eq('admin')->exec();

$result   = $action->undeleteTest($actionIDList[9]);
$userView = $tester->dao->select('*')->from('zt_userview')->where('account')->eq('admin')->fetch();
r($result && strpos($userView->programs . ',', ',7,') !== false) && p('') && e('1');                                                      // 测试还原action 9, objectType program 的数据,并且测试是否恢复了用户访问权限。

$result   = $action->undeleteTest($actionIDList[10]);
$userView = $tester->dao->select('*')->from('zt_userview')->where('account')->eq('admin')->fetch();
r($result && strpos($userView->products . ',', ',8,') !== false) && p('') && e('0');                                                      // 测试还原action 10, objectType project 的数据,并且测试是否恢复了用户访问权限。

$tester->dao->update('zt_userview')->set('sprints')->eq('')->set('programs')->eq('')->set('products')->eq('')->set('projects')->eq('')->where('account')->eq('admin')->exec();

$result   = $action->undeleteTest($actionIDList[11]);
$userView = $tester->dao->select('*')->from('zt_userview')->where('account')->eq('admin')->fetch();
r($result && strpos($userView->projects . ',', ',9,') !== false) && p('') && e('1');                                                      // 测试还原action 11, objectType project 的数据,并且测试是否恢复了用户访问权限。

$result   = $action->undeleteTest($actionIDList[12]);
$product = $tester->dao->select('*')->from('zt_product')->where('id')->eq('2')->fetch();
$doclib  = $tester->dao->select('*')->from('zt_doclib')->where('type')->eq('execution')->andWhere('execution')->eq('4')->fetch();
r($result && $product->deleted == 0 && !$doclib->deleted) && p('') && e('1');                                                             // 测试还原action 12, objectTyp prodcut 的数据,并且测试是否恢复了用户访问权限。

r($action->undeleteTest($actionIDList[13])) && p('') && e('模块名“模块”已经存在！');                                                      // 测试还原action 13, objectType module数据。
r($action->undeleteTest($actionIDList[14])) && p('') && e('1');                                                                           // 测试还原action 14, objectType module 的数据。

//r($action->undeleteTest($actionIDList[15])) && p('') && e('1');                                                                         // 测试还原action 15, objectType execution 的数据。
//r($action->undeleteTest($actionIDList[16])) && p('') && e('1');                                                                         // 测试还原action 16, objectType execution 的数据。

r($action->undeleteTest($actionIDList[17])) && p('') && e('1');                                                                           // 测试还原action 17, objectType release 的数据。

$result = $action->undeleteTest($actionIDList[18]);
$build  = $tester->dao->select('*')->from('zt_build')->where('id')->eq('1')->fetch();
r($result && !$build->deleted) && p('') && e('1');                                                                                        // 测试还原action 18, objectType release 的数据。

r($action->undeleteTest($actionIDList[19])) && p('') && e('还原用例之前，请先还原该用例所属场景');                                        // 测试还原action 19, objectType case 的数据。
r($action->undeleteTest($actionIDList[20])) && p('') && e('1');                                                                           // 测试还原action 20, objectType case 的数据。

r($action->undeleteTest($actionIDList[21])) && p('') && e('还原场景之前，请先还原该场景的父场景');                                        // 测试还原action 21, objectType scene 的数据。
r($action->undeleteTest($actionIDList[22])) && p('') && e('1');                                                                           // 测试还原action 22, objectType scene 的数据。

$result = r($action->undeleteTest($actionIDList[23]));
$file   = $tester->dao->select('*')->from('zt_file')->where('id')->eq('1')->fetch();
r($result && !$file->deleted) && p('') && e('1');                                                                                         // 测试还原action 23, objectType doc 的数据。

$result = $action->undeleteTest($actionIDList[24]);
$parentPlan = $tester->dao->select('*')->from('zt_productplan')->where('id')->eq('1')->fetch();
r($result && $parentPlan->parent == '0') && p('') && e('1');                                                                              // 测试还原action 24, objectType productplan 的数据。

$result = $action->undeleteTest($actionIDList[25]);
$task   = $tester->dao->select('*')->from('zt_task')->where('id')->eq('1')->fetch();
r($result && $task->parent == '-1') && p('') && e('1');                                                                                   // 测试还原action 25, objectType task 的数据。