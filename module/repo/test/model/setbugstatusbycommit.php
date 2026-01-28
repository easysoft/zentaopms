#!/usr/bin/env php
<?php

/**

title=测试 repoModel::setBugStatusByCommit();
timeout=0
cid=18102

- 步骤1：正常resolve操作
 - 属性1 @1
 - 属性2 @2
- 步骤2：已resolved状态的Bug处理 @1
- 步骤3：空数组输入 @1
- 步骤4：不存在的Bug ID处理
 - 属性999 @999
 - 属性1000 @1000
- 步骤5：空actions数组处理
 - 属性3 @3
 - 属性4 @4

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备测试数据
$bug = zenData('bug');
$bug->id->range('1-10');
$bug->product->range('1');
$bug->execution->range('0{5},101{5}');
$bug->openedBy->range('admin,user1,user2');
$bug->status->range('active{5},resolved{3},closed{2}');
$bug->resolution->range('',',fixed{3},duplicate{2}');
$bug->gen(10);

zenData('repo')->loadYaml('repo')->gen(4);

// 用户登录
su('admin');

// 创建测试实例
$repoTest = new repoModelTest();

// 准备基础action对象
$baseAction = new stdclass();
$baseAction->actor  = 'admin';
$baseAction->date   = '2023-12-29 13:14:36';
$baseAction->extra  = '61e51cadb1';
$baseAction->action = 'gitcommited';

// 准备基础changes数组
$baseChanges = array();

// 测试步骤1：正常情况下修复active状态的Bug
$bugs1 = array(1 => 1, 2 => 2);
$actions1 = array('bug' => array(1 => array('resolve' => array()), 2 => array('resolve' => array())));
$result1 = $repoTest->setBugStatusByCommitTest($bugs1, $actions1, $baseAction, $baseChanges);
r($result1) && p('1,2') && e('1,2'); // 步骤1：正常resolve操作

// 测试步骤2：测试已经resolved状态的Bug
$bugs2 = array(6 => 6, 7 => 7);
$actions2 = array('bug' => array(6 => array('resolve' => array()), 7 => array('resolve' => array())));
$result2 = $repoTest->setBugStatusByCommitTest($bugs2, $actions2, $baseAction, $baseChanges);
r(is_array($result2) ? 1 : 0) && p() && e('1'); // 步骤2：已resolved状态的Bug处理

// 测试步骤3：测试空Bug数组输入
$bugs3 = array();
$actions3 = array('bug' => array());
$result3 = $repoTest->setBugStatusByCommitTest($bugs3, $actions3, $baseAction, $baseChanges);
r(is_array($result3) ? 1 : 0) && p() && e('1'); // 步骤3：空数组输入

// 测试步骤4：测试不存在的Bug ID
$bugs4 = array(999 => 999, 1000 => 1000);
$actions4 = array('bug' => array(999 => array('resolve' => array()), 1000 => array('resolve' => array())));
$result4 = $repoTest->setBugStatusByCommitTest($bugs4, $actions4, $baseAction, $baseChanges);
r($result4) && p('999,1000') && e('999,1000'); // 步骤4：不存在的Bug ID处理

// 测试步骤5：测试空actions数组
$bugs5 = array(3 => 3, 4 => 4);
$actions5 = array();
$result5 = $repoTest->setBugStatusByCommitTest($bugs5, $actions5, $baseAction, $baseChanges);
r($result5) && p('3,4') && e('3,4'); // 步骤5：空actions数组处理