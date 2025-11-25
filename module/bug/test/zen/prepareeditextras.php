#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

// zendata数据准备
$bug = zenData('bug');
$bug->loadYaml('bug_prepareeditextras', false, 2)->gen(10);

su('admin');

/**

title=测试 bugZen::prepareEditExtras();
timeout=0
cid=15467

- 执行invokeArgs($zen模块的newInstance方法，参数是, [$formData1, $oldBug1] 属性id @1
- 执行invokeArgs($zen模块的newInstance方法，参数是, [$formData2, $oldBug2]  @0
- 执行invokeArgs($zen模块的newInstance方法，参数是, [$formData3, $oldBug3] 属性assignedTo @user1
- 执行invokeArgs($zen模块的newInstance方法，参数是, [$formData4, $oldBug4] 
 - 属性status @resolved
 - 属性confirmed @1
- 执行invokeArgs($zen模块的newInstance方法，参数是, [$formData5, $oldBug5] 
 - 属性status @closed
 - 属性assignedTo @closed

*/

global $tester, $app;
$app->rawModule = 'bug';
$app->rawMethod = 'edit';

// 创建测试用的form对象
function createFormMock($assignedTo, $resolution = '', $resolvedBy = '', $closedBy = '', $closedDate = '') {
    global $tester;
    
    // 设置POST数据模拟表单提交
    $_POST['assignedTo'] = $assignedTo;
    $_POST['resolution'] = $resolution;
    $_POST['resolvedBy'] = $resolvedBy;
    $_POST['resolvedDate'] = '';
    $_POST['closedBy'] = $closedBy;
    $_POST['closedDate'] = $closedDate;
    $_POST['title'] = 'Test Bug';
    $_POST['type'] = 'codeerror';
    $_POST['product'] = 1;
    $_POST['execution'] = 0;
    $_POST['openedBuild'] = 'trunk';
    $_POST['pri'] = 3;
    $_POST['severity'] = 3;
    $_POST['steps'] = 'Test steps';
    
    // 使用真实的form类创建对象
    $formData = form::data($tester->config->bug->form->edit);
    
    return $formData;
}

// 准备基础Bug数据
$baseBug = (object)array(
    'id' => 1,
    'product' => 1,
    'assignedTo' => 'admin',
    'status' => 'active',
    'lastEditedDate' => '2023-05-04 14:00:00',
    'openedBy' => 'admin'
);

$zen = initReference('bug');
$func = $zen->getMethod('prepareEditExtras');

// 测试1：正常编辑Bug数据处理
$_POST['lastEditedDate'] = '2023-05-04 14:00:00';
$formData1 = createFormMock('admin');
$oldBug1 = clone $baseBug;
r($func->invokeArgs($zen->newInstance(), [$formData1, $oldBug1])) && p('id') && e('1');

// 测试2：测试并发编辑冲突检测
$_POST['lastEditedDate'] = '2023-05-04 14:00:00';
$formData2 = createFormMock('user1');
$oldBug2 = clone $baseBug;
$oldBug2->lastEditedDate = '2023-05-03 13:00:00'; // 设置不同的编辑时间
r($func->invokeArgs($zen->newInstance(), [$formData2, $oldBug2])) && p() && e('0');

// 测试3：测试指派人员变更时更新指派日期
$_POST['lastEditedDate'] = '2023-05-04 14:00:00';
$formData3 = createFormMock('user1');
$oldBug3 = clone $baseBug;
$oldBug3->assignedTo = 'admin'; // 原指派人与新指派人不同
r($func->invokeArgs($zen->newInstance(), [$formData3, $oldBug3])) && p('assignedTo') && e('user1');

// 测试4：测试解决Bug时自动设置状态和解决日期
$_POST['lastEditedDate'] = '2023-05-04 14:00:00';
$formData4 = createFormMock('user1', 'fixed', 'admin');
$oldBug4 = clone $baseBug;
r($func->invokeArgs($zen->newInstance(), [$formData4, $oldBug4])) && p('status,confirmed') && e('resolved,1');

// 测试5：测试关闭Bug时自动设置状态和关闭日期（必须先解决）
$_POST['lastEditedDate'] = '2023-05-04 14:00:00';
$formData5 = createFormMock('user1', 'fixed', 'admin', 'admin', '2023-05-10 10:00:00');
$oldBug5 = clone $baseBug;
r($func->invokeArgs($zen->newInstance(), [$formData5, $oldBug5])) && p('status,assignedTo') && e('closed,closed');