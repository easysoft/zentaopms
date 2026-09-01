#!/usr/bin/env php
<?php

function ztfVal($value)
{
    if($value === null) return 'null';
    if(is_bool($value)) return $value ? '1' : '0';
    if(is_scalar($value))
    {
        $string = (string)$value;
        if(strlen($string) > 80) return 'string_len=' . strlen($string);
        return $string;
    }
    if(is_array($value))
    {
        if(empty($value)) return 'empty_array';
        $first = reset($value);
        if(is_object($first) || is_array($first)) return 'array_count=' . count($value);
        $parts = array();
        foreach($value as $item) $parts[] = is_scalar($item) ? (string)$item : 'obj';
        return 'array:' . implode(',', $parts);
    }
    if(is_object($value))
    {
        $vars = get_object_vars($value);
        if(empty($vars)) return 'empty_object';
        foreach(array('id', 'name', 'title', 'status', 'type', 'code', 'account', 'module', 'field', 'value') as $key)
        {
            if(array_key_exists($key, $vars)) return $key . '=' . (is_scalar($vars[$key]) ? $vars[$key] : 'object');
        }
        return 'object_count=' . count($vars);
    }
    return 'unknown';
}

function ztfCall($callable)
{
    try
    {
        ob_start();
        $result = $callable();
        $echoed = ob_get_clean();
        if($echoed !== '') return 'echo_yes';
        if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
        return $result;
    }
    catch(Throwable $e)
    {
        if(ob_get_level()) ob_end_clean();
        if($e instanceof EndResponseException) return 0;
        return 'error:' . get_class($e);
    }
}

function ztfInvoke($object, $method, array $args)
{
    $reflection = new ReflectionMethod($object, $method);
    $reflection->setAccessible(true);
    return $reflection->invokeArgs($object, $args);
}

include dirname(__FILE__, 5) . '/test/lib/init.php';

error_reporting(E_ERROR);

$zd_workflowaction = zenData('workflowaction');
$zd_workflowaction->id->range('1-5');
$zd_workflowaction->group->range('1-5');
$zd_workflowaction->module->range('1-5');
$zd_workflowaction->action->range('1-5');
$zd_workflowaction->vision->range('1-5');
$zd_workflowaction->order->range('0');
$zd_workflowaction->buildin->range('0');
$zd_workflowaction->virtual->range('0');
$zd_workflowaction->createdDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_workflowaction->editedDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_workflowaction->gen(5);
$zd_user = zenData('user');
$zd_user->id->range('1-1');
$zd_user->account->range('1-1');
$zd_user->last->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_user->feedback->range('0');
$zd_user->scoreLevel->range('0');
$zd_user->resetExpired->range('0');
$zd_user->jira->range('0');
$zd_user->deleted->range('0');
$zd_user->gen(1);

su('admin');

$tester->loadModel('ai');

/**

title=测试 aiModel::getWorkflowActionForPrompt()
timeout=0
cid=0

- 步骤1：正常输入 @name=
- 步骤2：边界值输入 @0
- 步骤3：无效输入 @0
- 步骤4：大值输入 @0
- 步骤5：业务规则验证 @0

*/

r(ztfVal(ztfCall(function() use ($tester) { return $tester->ai->getWorkflowActionForPrompt('1', '1'); }))) && p() && e('name='); // 步骤1：正常输入
r(ztfVal(ztfCall(function() use ($tester) { return $tester->ai->getWorkflowActionForPrompt('', '1'); }))) && p() && e('0'); // 步骤2：边界值输入
r(ztfVal(ztfCall(function() use ($tester) { return $tester->ai->getWorkflowActionForPrompt('abc', '1'); }))) && p() && e('0'); // 步骤3：无效输入
r(ztfVal(ztfCall(function() use ($tester) { return $tester->ai->getWorkflowActionForPrompt('999999', '1'); }))) && p() && e('0'); // 步骤4：大值输入
r(ztfVal(ztfCall(function() use ($tester) { return $tester->ai->getWorkflowActionForPrompt('test', 'test'); }))) && p() && e('0'); // 步骤5：业务规则验证
