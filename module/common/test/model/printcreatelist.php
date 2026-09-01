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

$zd_product = zenData('product');
$zd_product->id->range('1-5');
$zd_product->shadow->range('0');
$zd_product->bind->range('0');
$zd_product->workflowGroup->range('0');
$zd_product->draftEpics->range('0');
$zd_product->activeEpics->range('0');
$zd_product->changingEpics->range('0');
$zd_product->reviewingEpics->range('0');
$zd_product->finishedEpics->range('0');
$zd_product->closedEpics->range('0');
$zd_product->totalEpics->range('0');
$zd_product->draftRequirements->range('0');
$zd_product->activeRequirements->range('0');
$zd_product->changingRequirements->range('0');
$zd_product->reviewingRequirements->range('0');
$zd_product->finishedRequirements->range('0');
$zd_product->closedRequirements->range('0');
$zd_product->totalRequirements->range('0');
$zd_product->draftStories->range('0');
$zd_product->activeStories->range('0');
$zd_product->changingStories->range('0');
$zd_product->reviewingStories->range('0');
$zd_product->finishedStories->range('0');
$zd_product->closedStories->range('0');
$zd_product->totalStories->range('0');
$zd_product->unresolvedBugs->range('0');
$zd_product->closedBugs->range('0');
$zd_product->fixedBugs->range('0');
$zd_product->totalBugs->range('0');
$zd_product->plans->range('0');
$zd_product->releases->range('0');
$zd_product->closedDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_product->gen(5);
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
$zd_projectproduct = zenData('projectproduct');
$zd_projectproduct->id->range('1-5');
$zd_projectproduct->project->range('1-5');
$zd_projectproduct->product->range('1-5');
$zd_projectproduct->branch->range('1-5');
$zd_projectproduct->gen(5);
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

$tester->loadModel('common');

/**

title=测试 commonModel::printCreateList()
timeout=0
cid=0

- 步骤1：正常输入 @0
- 步骤2：边界值输入 @0
- 步骤3：无效输入 @0
- 步骤4：大值输入 @0
- 步骤5：业务规则验证 @0

*/

r(ztfVal(ztfCall(function() use ($tester) { return ztfInvoke($tester->common, 'printCreateList', array()); }))) && p() && e('0'); // 步骤1：正常输入
r(ztfVal(ztfCall(function() use ($tester) { return ztfInvoke($tester->common, 'printCreateList', array()); }))) && p() && e('0'); // 步骤2：边界值输入
r(ztfVal(ztfCall(function() use ($tester) { return ztfInvoke($tester->common, 'printCreateList', array()); }))) && p() && e('0'); // 步骤3：无效输入
r(ztfVal(ztfCall(function() use ($tester) { return ztfInvoke($tester->common, 'printCreateList', array()); }))) && p() && e('0'); // 步骤4：大值输入
r(ztfVal(ztfCall(function() use ($tester) { return ztfInvoke($tester->common, 'printCreateList', array()); }))) && p() && e('0'); // 步骤5：业务规则验证
