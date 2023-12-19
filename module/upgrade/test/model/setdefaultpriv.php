#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->setDefaultPriv();
cid=1

- 测试设置项目集默认权限后，获取权限分组 1 的方法 @index,pgmindex,prjbrowse

- 测试设置项目集默认权限后，获取权限分组 2 的方法 @index,pgmindex,prjbrowse

- 测试设置项目集默认权限后，获取权限分组 3 的方法 @index,pgmindex,prjbrowse

- 测试设置项目集默认权限后，获取权限分组 4 的方法 @activate,batchUnlinkStakeholders,browse,close,create,createStakeholder,delete,edit,kanban,product,productView,project,stakeholder,start,suspend,unbindWhitelist,unlinkStakeholder,updateOrder,view

- 测试设置项目集默认权限后，获取权限分组 5 的方法 @index,pgmindex,prjbrowse

- 测试设置项目集默认权限后，获取权限分组 6 的方法 @index,pgmindex,prjbrowse

- 测试设置项目集默认权限后，获取权限分组 7 的方法 @index,pgmindex,prjbrowse

- 测试设置项目集默认权限后，获取权限分组 8 的方法 @0
- 测试设置项目集默认权限后，获取不存在的权限分组 9 的方法 @0

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

zdTable('user')->config('user')->gen(5);
zdTable('group')->config('group')->gen(8);
zdTable('grouppriv')->gen(0);

su('admin');

$upgrade = new upgradeTest();
$groupPrivs = $upgrade->setDefaultPrivTest();

global $tester;
$groupPrivs1 = $tester->dao->select('method')->from(TABLE_GROUPPRIV)->where('`group`')->eq('1')->fetchPairs('method');
$groupPrivs2 = $tester->dao->select('method')->from(TABLE_GROUPPRIV)->where('`group`')->eq('2')->fetchPairs('method');
$groupPrivs3 = $tester->dao->select('method')->from(TABLE_GROUPPRIV)->where('`group`')->eq('3')->fetchPairs('method');
$groupPrivs4 = $tester->dao->select('method')->from(TABLE_GROUPPRIV)->where('`group`')->eq('4')->fetchPairs('method');
$groupPrivs5 = $tester->dao->select('method')->from(TABLE_GROUPPRIV)->where('`group`')->eq('5')->fetchPairs('method');
$groupPrivs6 = $tester->dao->select('method')->from(TABLE_GROUPPRIV)->where('`group`')->eq('6')->fetchPairs('method');
$groupPrivs7 = $tester->dao->select('method')->from(TABLE_GROUPPRIV)->where('`group`')->eq('7')->fetchPairs('method');
$groupPrivs8 = $tester->dao->select('method')->from(TABLE_GROUPPRIV)->where('`group`')->eq('8')->fetchPairs('method');
$groupPrivs9 = $tester->dao->select('method')->from(TABLE_GROUPPRIV)->where('`group`')->eq('9')->fetchPairs('method');

r(implode(',', $groupPrivs1)) && p() && e('index,pgmindex,prjbrowse'); // 测试设置项目集默认权限后，获取权限分组 1 的方法
r(implode(',', $groupPrivs2)) && p() && e('index,pgmindex,prjbrowse'); // 测试设置项目集默认权限后，获取权限分组 2 的方法
r(implode(',', $groupPrivs3)) && p() && e('index,pgmindex,prjbrowse'); // 测试设置项目集默认权限后，获取权限分组 3 的方法
r(implode(',', $groupPrivs4)) && p() && e('activate,batchUnlinkStakeholders,browse,close,create,createStakeholder,delete,edit,kanban,product,productView,project,stakeholder,start,suspend,unbindWhitelist,unlinkStakeholder,updateOrder,view'); // 测试设置项目集默认权限后，获取权限分组 4 的方法
r(implode(',', $groupPrivs5)) && p() && e('index,pgmindex,prjbrowse'); // 测试设置项目集默认权限后，获取权限分组 5 的方法
r(implode(',', $groupPrivs6)) && p() && e('index,pgmindex,prjbrowse'); // 测试设置项目集默认权限后，获取权限分组 6 的方法
r(implode(',', $groupPrivs7)) && p() && e('index,pgmindex,prjbrowse'); // 测试设置项目集默认权限后，获取权限分组 7 的方法
r(implode(',', $groupPrivs8)) && p() && e('0');                        // 测试设置项目集默认权限后，获取权限分组 8 的方法
r(implode(',', $groupPrivs9)) && p() && e('0');                        // 测试设置项目集默认权限后，获取不存在的权限分组 9 的方法
