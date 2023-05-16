#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/bug.class.php';

zdTable('project')->config('project_checkrequired4resolve')->gen(2);

global $tester;

/**

title=bugTao->checkRequired4Resolve();
timeout=0
cid=1


*/

$emptyString    = '';
$emptyInt       = 0;
$resolutionList = array('bydesign', 'duplicate');
$resolvedDate   = '2023-01-01 00:00:00';
$assignedTo     = 'admin';
$comment        = '一个备注';
$execution      = 1;
$oldExecution   = array(11,12);
$duplicateBug   = 1;
$buildName      = '新建的版本';

$bug = new bugTest();

r($bug->checkRequired4ResolveTest($emptyString,       $resolvedDate, $assignedTo,  $comment,     $execution, $buildName))   && p() && e('『解决方案』不能为空。');   // 检查解决方案为空时的必填项
r($bug->checkRequired4ResolveTest($resolutionList[0], $emptyString,  $assignedTo,  $comment,     $execution, $buildName))   && p() && e('『解决日期』不能为空。');   // 检查解决日期为空时的必填项
r($bug->checkRequired4ResolveTest($resolutionList[0], $resolvedDate, $emptyString, $comment,     $execution, $buildName))   && p() && e('『指派给』不能为空。');     // 检查指派人为空时的必填项
r($bug->checkRequired4ResolveTest($resolutionList[0], $resolvedDate, $assignedTo,  $emptyString, $execution, $buildName))   && p() && e('『备注』不能为空。');       // 检查注释为空时的必填项
r($bug->checkRequired4ResolveTest($resolutionList[0], $resolvedDate, $assignedTo,  $comment,     $execution, $emptyString)) && p() && e('『新版本名称』不能为空。'); // 检查版本名称为空时的必填项

r($bug->checkRequired4ResolveTest($resolutionList[0], $resolvedDate, $assignedTo, $comment, $emptyInt, $buildName, $oldExecution[0])) && p() && e('『所属执行』不能为空。'); // 检查执行为空 原有执行为scrum类型时的必填项
r($bug->checkRequired4ResolveTest($resolutionList[0], $resolvedDate, $assignedTo, $comment, $emptyInt, $buildName, $oldExecution[1])) && p() && e('『所属看板』不能为空。'); // 检查执行为空 原有执行为kanban类型时的必填项

r($bug->checkRequired4ResolveTest($resolutionList[0], $resolvedDate, $assignedTo, $comment, $execution, $buildName, $oldExecution[0], $emptyInt))     && p() && e('no error');              // 检查解决方案为设计如此 重复BUGid为空时的必填项
r($bug->checkRequired4ResolveTest($resolutionList[0], $resolvedDate, $assignedTo, $comment, $execution, $buildName, $oldExecution[0], $duplicateBug)) && p() && e('no error');              // 检查解决方案为设计如此 重复BUGid非空时的必填项
r($bug->checkRequired4ResolveTest($resolutionList[1], $resolvedDate, $assignedTo, $comment, $execution, $buildName, $oldExecution[0], $emptyInt))     && p() && e('『重复Bug』不能为空。'); // 检查解决方案为重复 重复BUGid为空时的必填项
r($bug->checkRequired4ResolveTest($resolutionList[1], $resolvedDate, $assignedTo, $comment, $execution, $buildName, $oldExecution[0], $duplicateBug)) && p() && e('no error');              // 检查解决方案为重复 重复BUGid非空时的必填项

r($bug->checkRequired4ResolveTest($resolutionList[1], $emptyString, $emptyString, $emptyString, $emptyInt, $emptyString, $oldExecution[0], $emptyInt))     && p() && e('『所属执行』不能为空。『解决日期』不能为空。『指派给』不能为空。『备注』不能为空。『重复Bug』不能为空。『新版本名称』不能为空。'); // 检查解决方案为重复 重复BUGid为空 没有解决日期 指派人 注释 版本名称 执行时的必填项
r($bug->checkRequired4ResolveTest($resolutionList[1], $emptyString, $emptyString, $emptyString, $emptyInt, $emptyString, $oldExecution[0], $duplicateBug)) && p() && e('『所属执行』不能为空。『解决日期』不能为空。『指派给』不能为空。『备注』不能为空。『新版本名称』不能为空。');                      // 检查解决方案为重复 重复BUGid非空 没有解决日期 指派人 注释 版本名称 执行时的必填项
