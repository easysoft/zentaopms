#!/usr/bin/env php
<?php

function ztfVal($value)
{
    if($value === null) return 'null';
    if(is_bool($value)) return $value ? '1' : '0';
    if(is_scalar($value))
    {
        $string = str_replace(array("'", '"', '\\'), '', (string)$value);
        if(strpos($string, '.php') !== false || strpos($string, 'http') !== false || strpos($string, 'href=') !== false) return 'string_with_url';
        if(strlen($string) > 80) return 'string_len=' . strlen($string);
        return $string;
    }
    if(is_array($value))
    {
        if(empty($value)) return 'empty_array';
        $first = reset($value);
        if(is_object($first) || is_array($first)) return 'array_count=' . count($value);
        $parts = array();
        foreach($value as $item) $parts[] = is_scalar($item) ? str_replace(array("'", '"', '\\'), '', (string)$item) : 'obj';
        return 'array:' . implode(',', $parts);
    }
    if(is_object($value))
    {
        $vars = get_object_vars($value);
        if(empty($vars)) return 'empty_object';
        foreach(array('id', 'name', 'title', 'status', 'type', 'code', 'account', 'module', 'field', 'value') as $key)
        {
            if(array_key_exists($key, $vars)) return $key . '=' . (is_scalar($vars[$key]) ? str_replace(array("'", '"', '\\'), '', (string)$vars[$key]) : 'object');
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

$zd_task = zenData('task');
$zd_task->id->range('1-5');
$zd_task->parent->range('0');
$zd_task->isParent->range('0');
$zd_task->isTpl->range('0');
$zd_task->design->range('0');
$zd_task->designVersion->range('0');
$zd_task->fromBug->range('0');
$zd_task->feedback->range('0');
$zd_task->fromIssue->range('0');
$zd_task->finishedDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_task->canceledDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_task->closedDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_task->lastEditedDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_task->activatedDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_task->order->range('0');
$zd_task->repo->range('0');
$zd_task->mr->range('0');
$zd_task->deleted->range('0');
$zd_task->gen(5);
$zd_project = zenData('project');
$zd_project->id->range('1-5');
$zd_project->isTpl->range('0');
$zd_project->charter->range('0');
$zd_project->milestone->range('0');
$zd_project->workflowGroup->range('0');
$zd_project->firstEnd->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD');
$zd_project->realBegan->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD');
$zd_project->realEnd->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD');
$zd_project->days->range('0');
$zd_project->pri->range('0');
$zd_project->version->range('0');
$zd_project->parentVersion->range('0');
$zd_project->planDuration->range('0');
$zd_project->realDuration->range('0');
$zd_project->estimate->range('0');
$zd_project->left->range('0');
$zd_project->consumed->range('0');
$zd_project->teamCount->range('0');
$zd_project->market->range('0');
$zd_project->PI->range('0');
$zd_project->lastEditedDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_project->closedDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_project->canceledDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_project->suspendedDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_project->displayCards->range('0');
$zd_project->fluidBoard->range('0');
$zd_project->parallel->range('0');
$zd_project->colWidth->range('0');
$zd_project->minColWidth->range('0');
$zd_project->maxColWidth->range('0');
$zd_project->coverExecutionPriv->range('0');
$zd_project->deleted->range('0');
$zd_project->gen(5);
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

$tester->loadModel('search');
$tester->loadTao('search');

/**

title=测试 searchModel::processTaskRecord()
timeout=0
cid=0

- 步骤1：正常输入 @0
- 步骤2：边界值输入 @0
- 步骤3：无效输入 @0
- 步骤4：大值输入 @0
- 步骤5：业务规则验证 @0

*/

r(ztfVal(ztfCall(function() use ($tester) { return $tester->searchTao->processTaskRecord((object)array('id' => 1, 'name' => 'test', 'status' => 'doing', 'begin' => date('Y-m-d'), 'end' => date('Y-m-d', strtotime('+1 day')))); }))) && p() && e('0'); // 步骤1：正常输入
r(ztfVal(ztfCall(function() use ($tester) { return $tester->searchTao->processTaskRecord((object)array()); }))) && p() && e('0'); // 步骤2：边界值输入
r(ztfVal(ztfCall(function() use ($tester) { return $tester->searchTao->processTaskRecord((object)array()); }))) && p() && e('0'); // 步骤3：无效输入
r(ztfVal(ztfCall(function() use ($tester) { return $tester->searchTao->processTaskRecord((object)array('id' => 999999)); }))) && p() && e('0'); // 步骤4：大值输入
r(ztfVal(ztfCall(function() use ($tester) { return $tester->searchTao->processTaskRecord((object)array('id' => 1, 'name' => 'test', 'status' => 'doing', 'begin' => date('Y-m-d'), 'end' => date('Y-m-d', strtotime('+1 day')))); }))) && p() && e('0'); // 步骤5：业务规则验证
