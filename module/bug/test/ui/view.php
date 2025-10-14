#!/usr/bin/env php
<?php
include dirname(__FILE__, 2) . '/lib/ui/viewbug.ui.class.php';

/**

title=bug详情页检查测试
timeout=0
cid=1

- 测试bug详情页面标题
 - 最终测试状态 @SUCCESS
 - 测试结果 @Bug详情页面标题检查成功
- 测试bug详情页面信息区域
 - 最终测试状态 @SUCCESS
 - 测试结果 @Bug详情页面信息区域检查成功
- 检查bug详情页面下部操作按钮显示
 - 最终测试状态 @SUCCESS
 - 测试结果 @Bug详情页面下部操作按钮检查成功
- 测试bug详情页面右部标签切换
 - 最终测试状态 @SUCCESS
 - 测试结果 @Bug详情页面右部标签切换测试成功

*/

zenData('product')->loadYaml('product')->gen(1);

$bug = zenData('bug');
$bug->project->range('0');
$bug->product->range('1');
$bug->module->range('0');
$bug->execution->range('0');
$bug->openedBuild->range('trunk');
$bug->assignedTo->range('admin');
$bug->gen(1);

$user = zenData('user');
$user->id->range('1-3');
$user->account->range('admin, user1, user2');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->realname->range('admin, USER1, USER2');
$user->gen(3);

$bug = (object)array('id' => 1, 'title' => 'BUG1');

$tester = new viewBugTester();

r($tester->viewBugTitle($bug))           && p('status,message') && e('SUCCESS,Bug详情页面标题检查成功');         // 测试bug详情页面标题
r($tester->checkBugDetailSections($bug)) && p('status,message') && e('SUCCESS,Bug详情页面信息区域检查成功');     // 测试bug详情页面信息区域
r($tester->checkActionButtons($bug))     && p('status,message') && e('SUCCESS,Bug详情页面下部操作按钮检查成功'); // 检查bug详情页面下部操作按钮显示
r($tester->testDetailTabs($bug))         && p('status,message') && e('SUCCESS,Bug详情页面右部标签切换测试成功'); // 测试bug详情页面右部标签切换

$tester->closeBrowser();