#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';
su('admin');

/**

title=测试executionModel->isClickableTest();
timeout=0
cid=16351

- wait状态执行start按钮检查 @检查通过
- wait状态执行close按钮检查 @检查通过
- wait状态执行suspend按钮检查 @检查通过
- wait状态执行putoff按钮检查 @检查通过
- wait状态执行activate按钮检查 @检查不通过
- wait状态执行delete按钮检查 @检查通过
- doing状态执行start按钮检查 @检查不通过
- doing状态执行close按钮检查 @检查通过
- doing状态执行suspend按钮检查 @检查通过
- doing状态执行putoff按钮检查 @检查通过
- doing状态执行activate按钮检查 @检查不通过
- doing状态执行delete按钮检查 @检查通过
- suspended状态执行start按钮检查 @检查不通过
- suspended状态执行close按钮检查 @检查通过
- suspended状态执行suspend按钮检查 @检查不通过
- suspended状态执行putoff按钮检查 @检查不通过
- suspended状态执行activate按钮检查 @检查通过
- suspended状态执行delete按钮检查 @检查通过
- closed状态执行start按钮检查 @检查不通过
- closed状态执行close按钮检查 @检查不通过
- closed状态执行suspend按钮检查 @检查不通过
- closed状态执行putoff按钮检查 @检查不通过
- closed状态执行activate按钮检查 @检查通过
- closed状态执行delete按钮检查 @检查通过

*/

$waitExecution      = new stdclass();
$doingExecution     = new stdclass();
$suspendedExecution = new stdclass();
$closedExecution    = new stdclass();

$waitExecution->status      = 'wait';
$doingExecution->status     = 'doing';
$suspendedExecution->status = 'suspended';
$closedExecution->status    = 'closed';

$actionList = array('start', 'close', 'suspend', 'putoff', 'activate', 'delete');

$execution = new executionTest();
r($execution->isClickableTest($waitExecution,      $actionList[0])) && p() && e('检查通过');   // wait状态执行start按钮检查
r($execution->isClickableTest($waitExecution,      $actionList[1])) && p() && e('检查通过');   // wait状态执行close按钮检查
r($execution->isClickableTest($waitExecution,      $actionList[2])) && p() && e('检查通过');   // wait状态执行suspend按钮检查
r($execution->isClickableTest($waitExecution,      $actionList[3])) && p() && e('检查通过');   // wait状态执行putoff按钮检查
r($execution->isClickableTest($waitExecution,      $actionList[4])) && p() && e('检查不通过'); // wait状态执行activate按钮检查
r($execution->isClickableTest($waitExecution,      $actionList[5])) && p() && e('检查通过');   // wait状态执行delete按钮检查
r($execution->isClickableTest($doingExecution,     $actionList[0])) && p() && e('检查不通过'); // doing状态执行start按钮检查
r($execution->isClickableTest($doingExecution,     $actionList[1])) && p() && e('检查通过');   // doing状态执行close按钮检查
r($execution->isClickableTest($doingExecution,     $actionList[2])) && p() && e('检查通过');   // doing状态执行suspend按钮检查
r($execution->isClickableTest($doingExecution,     $actionList[3])) && p() && e('检查通过');   // doing状态执行putoff按钮检查
r($execution->isClickableTest($doingExecution,     $actionList[4])) && p() && e('检查不通过'); // doing状态执行activate按钮检查
r($execution->isClickableTest($doingExecution,     $actionList[5])) && p() && e('检查通过');   // doing状态执行delete按钮检查
r($execution->isClickableTest($suspendedExecution, $actionList[0])) && p() && e('检查不通过'); // suspended状态执行start按钮检查
r($execution->isClickableTest($suspendedExecution, $actionList[1])) && p() && e('检查通过');   // suspended状态执行close按钮检查
r($execution->isClickableTest($suspendedExecution, $actionList[2])) && p() && e('检查不通过'); // suspended状态执行suspend按钮检查
r($execution->isClickableTest($suspendedExecution, $actionList[3])) && p() && e('检查不通过'); // suspended状态执行putoff按钮检查
r($execution->isClickableTest($suspendedExecution, $actionList[4])) && p() && e('检查通过');   // suspended状态执行activate按钮检查
r($execution->isClickableTest($suspendedExecution, $actionList[5])) && p() && e('检查通过');   // suspended状态执行delete按钮检查
r($execution->isClickableTest($closedExecution,    $actionList[0])) && p() && e('检查不通过'); // closed状态执行start按钮检查
r($execution->isClickableTest($closedExecution,    $actionList[1])) && p() && e('检查不通过'); // closed状态执行close按钮检查
r($execution->isClickableTest($closedExecution,    $actionList[2])) && p() && e('检查不通过'); // closed状态执行suspend按钮检查
r($execution->isClickableTest($closedExecution,    $actionList[3])) && p() && e('检查不通过'); // closed状态执行putoff按钮检查
r($execution->isClickableTest($closedExecution,    $actionList[4])) && p() && e('检查通过');   // closed状态执行activate按钮检查
r($execution->isClickableTest($closedExecution,    $actionList[5])) && p() && e('检查通过');   // closed状态执行delete按钮检查