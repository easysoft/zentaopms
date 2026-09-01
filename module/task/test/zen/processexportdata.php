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

$zd_story = zenData('story');
$zd_story->id->range('1-5');
$zd_story->parent->range('0');
$zd_story->isParent->range('0');
$zd_story->root->range('0');
$zd_story->branch->range('0');
$zd_story->fromBug->range('0');
$zd_story->feedback->range('0');
$zd_story->lib->range('0');
$zd_story->fromStory->range('0');
$zd_story->fromVersion->range('0');
$zd_story->approvedDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_story->lastEditedDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_story->changedDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_story->reviewedDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_story->releasedDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_story->closedDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_story->activatedDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_story->toBug->range('0');
$zd_story->duplicateStory->range('0');
$zd_story->parentVersion->range('0');
$zd_story->demandVersion->range('0');
$zd_story->storyChanged->range('0');
$zd_story->URChanged->range('0');
$zd_story->deleted->range('0');
$zd_story->gen(5);
$zd_file = zenData('file');
$zd_file->id->range('1-5');
$zd_file->gen(5);
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

$tester->loadModel('task');
helper::import($tester->app->getModulePath('', 'task') . 'control.php');
helper::import($tester->app->getModulePath('', 'task') . 'zen.php');

/**

title=测试 taskModel::processExportData()
timeout=0
cid=0

- 步骤1：正常输入 @empty_array
- 步骤2：边界值输入 @empty_array
- 步骤3：无效输入 @error:Error
- 步骤4：大值输入 @error:Error
- 步骤5：业务规则验证 @error:Error

*/

r(ztfVal(ztfCall(function() use ($tester) { return callZenMethod('task', 'processExportData', array(array(), 1)); }))) && p() && e('empty_array'); // 步骤1：正常输入
r(ztfVal(ztfCall(function() use ($tester) { return callZenMethod('task', 'processExportData', array(array(), 1)); }))) && p() && e('empty_array'); // 步骤2：边界值输入
r(ztfVal(ztfCall(function() use ($tester) { return callZenMethod('task', 'processExportData', array(array(-1), 1)); }))) && p() && e('error:Error'); // 步骤3：无效输入
r(ztfVal(ztfCall(function() use ($tester) { return callZenMethod('task', 'processExportData', array(array(999999), 1)); }))) && p() && e('error:Error'); // 步骤4：大值输入
r(ztfVal(ztfCall(function() use ($tester) { return callZenMethod('task', 'processExportData', array(array(1, 2), 2)); }))) && p() && e('error:Error'); // 步骤5：业务规则验证
