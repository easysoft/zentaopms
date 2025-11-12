#!/usr/bin/env php
<?php

/**

title=测试 bugZen::buildBugsForBatchEdit();
timeout=0
cid=0

- 测试步骤1:空参数输入返回空数组 @0
- 测试步骤2:验证os字段被正确处理第0条的os属性 @windows
- 测试步骤3:验证browser字段被正确处理第0条的browser属性 @firefox
- 测试步骤4:验证关闭状态的bug指派人保持不变第0条的assignedTo属性 @user1
- 测试步骤5:验证resolution非duplicate时duplicateBug设为0第0条的duplicateBug属性 @0
- 测试步骤6:验证assignedTo变化时自动设置assignedDate第0条的assignedDate属性 @`^[\d\-\:\s]+$`
- 测试步骤7:验证设置resolution时confirmed自动设为1第0条的confirmed属性 @1
- 测试步骤8:验证解决后状态变为resolved第0条的status属性 @resolved

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zendata('bug')->loadYaml('buildbugsforbatchedit/bug', false, 2)->gen(10);
zendata('product')->loadYaml('buildbugsforbatchedit/product', false, 2)->gen(3);
zendata('project')->loadYaml('buildbugsforbatchedit/project', false, 2)->gen(5);

su('admin');

global $tester, $app;
$app->rawModule = 'bug';
$app->rawMethod = 'batchedit';

$zen = initReference('bug');
$func = $zen->getMethod('buildBugsForBatchEdit');

// 测试步骤1:空参数输入
$oldBugs1 = array();
$result = $func->invokeArgs($zen->newInstance(), [$oldBugs1]);
r(count($result)) && p() && e('0'); // 测试步骤1:空参数输入返回空数组

// 准备老bug数据
$oldBug1 = new stdClass();
$oldBug1->id = 1;
$oldBug1->status = 'active';
$oldBug1->assignedTo = 'admin';
$oldBug1->openedBy = 'user1';

$oldBug2 = new stdClass();
$oldBug2->id = 2;
$oldBug2->status = 'closed';
$oldBug2->assignedTo = 'user1';
$oldBug2->openedBy = 'user2';

$oldBug3 = new stdClass();
$oldBug3->id = 3;
$oldBug3->status = 'active';
$oldBug3->assignedTo = 'user2';
$oldBug3->openedBy = 'user3';

// 测试步骤2:验证os数组字段转换
$_POST['id'] = array(1);
$_POST['title'] = array('测试Bug1');
$_POST['os'] = array('windows');
$_POST['browser'] = array('chrome');
$_POST['assignedTo'] = array('admin');
$_POST['resolution'] = array('');
$_POST['resolvedBy'] = array('');
$_POST['duplicateBug'] = array(0);
$_POST['openedBuild'] = array('trunk');
$oldBugs2 = array(1 => $oldBug1);
$result = $func->invokeArgs($zen->newInstance(), [$oldBugs2]);
r($result) && p('0:os') && e('windows'); // 测试步骤2:验证os字段被正确处理

// 测试步骤3:验证browser数组字段转换
$_POST['id'] = array(1);
$_POST['title'] = array('测试Bug1');
$_POST['os'] = array('windows');
$_POST['browser'] = array('firefox');
$_POST['assignedTo'] = array('admin');
$_POST['resolution'] = array('');
$_POST['resolvedBy'] = array('');
$_POST['duplicateBug'] = array(0);
$_POST['openedBuild'] = array('trunk');
$oldBugs3 = array(1 => $oldBug1);
$result = $func->invokeArgs($zen->newInstance(), [$oldBugs3]);
r($result) && p('0:browser') && e('firefox'); // 测试步骤3:验证browser字段被正确处理

// 测试步骤4:验证关闭状态bug指派人不变
$_POST['id'] = array(2);
$_POST['title'] = array('测试Bug2');
$_POST['os'] = array('mac');
$_POST['browser'] = array('safari');
$_POST['assignedTo'] = array('user3');
$_POST['resolution'] = array('');
$_POST['resolvedBy'] = array('');
$_POST['duplicateBug'] = array(0);
$_POST['openedBuild'] = array('trunk');
$oldBugs4 = array(2 => $oldBug2);
$result = $func->invokeArgs($zen->newInstance(), [$oldBugs4]);
r($result) && p('0:assignedTo') && e('user1'); // 测试步骤4:验证关闭状态的bug指派人保持不变

// 测试步骤5:验证resolution非duplicate时duplicateBug设为0
$_POST['id'] = array(1);
$_POST['title'] = array('测试Bug1');
$_POST['os'] = array('windows');
$_POST['browser'] = array('chrome');
$_POST['assignedTo'] = array('admin');
$_POST['resolution'] = array('fixed');
$_POST['resolvedBy'] = array('admin');
$_POST['duplicateBug'] = array(5);
$_POST['openedBuild'] = array('trunk');
$oldBugs5 = array(1 => $oldBug1);
$result = $func->invokeArgs($zen->newInstance(), [$oldBugs5]);
r($result) && p('0:duplicateBug') && e('0'); // 测试步骤5:验证resolution非duplicate时duplicateBug设为0

// 测试步骤6:验证assignedTo变化时设置assignedDate
$_POST['id'] = array(3);
$_POST['title'] = array('测试Bug3');
$_POST['os'] = array('linux');
$_POST['browser'] = array('firefox');
$_POST['assignedTo'] = array('user3');
$_POST['resolution'] = array('');
$_POST['resolvedBy'] = array('');
$_POST['duplicateBug'] = array(0);
$_POST['openedBuild'] = array('trunk');
$oldBugs6 = array(3 => $oldBug3);
$result = $func->invokeArgs($zen->newInstance(), [$oldBugs6]);
r($result) && p('0:assignedDate') && e('`^[\d\-\:\s]+$`'); // 测试步骤6:验证assignedTo变化时自动设置assignedDate

// 测试步骤7:验证设置resolution时confirmed自动设为1
$_POST['id'] = array(1);
$_POST['title'] = array('测试Bug1');
$_POST['os'] = array('windows');
$_POST['browser'] = array('chrome');
$_POST['assignedTo'] = array('admin');
$_POST['resolution'] = array('fixed');
$_POST['resolvedBy'] = array('admin');
$_POST['duplicateBug'] = array(0);
$_POST['openedBuild'] = array('trunk');
$oldBugs7 = array(1 => $oldBug1);
$result = $func->invokeArgs($zen->newInstance(), [$oldBugs7]);
r($result) && p('0:confirmed') && e('1'); // 测试步骤7:验证设置resolution时confirmed自动设为1

// 测试步骤8:验证解决后状态变为resolved
$_POST['id'] = array(1);
$_POST['title'] = array('测试Bug1');
$_POST['os'] = array('windows');
$_POST['browser'] = array('chrome');
$_POST['assignedTo'] = array('admin');
$_POST['resolution'] = array('fixed');
$_POST['resolvedBy'] = array('admin');
$_POST['duplicateBug'] = array(0);
$_POST['openedBuild'] = array('trunk');
$oldBugs8 = array(1 => $oldBug1);
$result = $func->invokeArgs($zen->newInstance(), [$oldBugs8]);
r($result) && p('0:status') && e('resolved'); // 测试步骤8:验证解决后状态变为resolved