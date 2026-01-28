#!/usr/bin/env php
<?php

/**

title=测试 buildModel::updateLinkedBug();
timeout=0
cid=15511

- 步骤1：正常更新Bug状态第1条的status属性 @resolved
- 步骤2：验证解决人设置第1条的resolvedBy属性 @admin
- 步骤3：验证解决版本设置第1条的resolvedBuild属性 @1
- 步骤4：空Bug列表测试 @0
- 步骤5：已解决Bug不重复处理第11条的status属性 @resolved
- 步骤6：已关闭Bug不重复处理第14条的status属性 @closed
- 步骤7：无效Bug ID测试 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备测试数据
$table = zenData('build');
$table->id->range('1-20');
$table->project->range('11,12,0{18}');
$table->product->range('1-3{7}');
$table->name->range('版本1,版本2,版本3{18}');
$table->gen(20);

$bugTable = zenData('bug');
$bugTable->id->range('1-20');
$bugTable->status->range('active{10},resolved{3},closed{2},active{5}');
$bugTable->resolution->range('[]{13},fixed{3},bydesign{2},[]{2}');
$bugTable->openedBy->range('admin,user1,user2{18}');
$bugTable->assignedTo->range('admin,dev1,test1{17}');
$bugTable->resolvedBy->range('[]{13},admin{3},user1{2},[]{2}');
$bugTable->resolvedBuild->range('[]{15},1{3},2{2}');
$bugTable->confirmed->range('0{10},1{10}');
$bugTable->gen(20);

zenData('user')->gen(5);

su('admin');

$build = new buildModelTest();

// 测试数据组合
$buildID = 1;
$validBugs = array(1, 2, 3);
$resolvedBugs = array(11, 12); // 已解决的Bug
$closedBugs = array(14, 15);   // 已关闭的Bug
$invalidBugs = array(999, 1000); // 不存在的Bug
$mixedBugs = array(1, 11, 999); // 混合状态Bug
$emptyBugs = array();

$resolvedByList = array(1 => 'admin', 2 => 'user1', 3 => 'user2');
$partialResolvedBy = array(1 => 'admin');

r($build->updateLinkedBugTest($buildID, array('bugs' => $validBugs, 'resolvedBy' => $resolvedByList))) && p('1:status') && e('resolved'); // 步骤1：正常更新Bug状态
r($build->updateLinkedBugTest($buildID, array('bugs' => $validBugs, 'resolvedBy' => $resolvedByList))) && p('1:resolvedBy') && e('admin'); // 步骤2：验证解决人设置
r($build->updateLinkedBugTest($buildID, array('bugs' => $validBugs, 'resolvedBy' => $resolvedByList))) && p('1:resolvedBuild') && e('1'); // 步骤3：验证解决版本设置
r($build->updateLinkedBugTest($buildID, array('bugs' => $emptyBugs, 'resolvedBy' => $resolvedByList))) && p() && e('0'); // 步骤4：空Bug列表测试
r($build->updateLinkedBugTest($buildID, array('bugs' => $resolvedBugs, 'resolvedBy' => array()))) && p('11:status') && e('resolved'); // 步骤5：已解决Bug不重复处理
r($build->updateLinkedBugTest($buildID, array('bugs' => $closedBugs, 'resolvedBy' => array()))) && p('14:status') && e('closed'); // 步骤6：已关闭Bug不重复处理
r($build->updateLinkedBugTest($buildID, array('bugs' => $invalidBugs, 'resolvedBy' => array()))) && p() && e('0'); // 步骤7：无效Bug ID测试