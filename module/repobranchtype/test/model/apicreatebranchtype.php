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

$zd_user = zenData('user');
$zd_user->id->range('1-1');
$zd_user->account->range('admin');
$zd_user->realname->range('admin');
$zd_user->last->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_user->feedback->range('0');
$zd_user->scoreLevel->range('0');
$zd_user->resetExpired->range('0');
$zd_user->jira->range('0');
$zd_user->deleted->range('0');
$zd_user->gen(1);

$zd_company = zenData('company');
$zd_company->id->range('1');
$zd_company->name->range('禅道软件');
$zd_company->admins->range('admin');
$zd_company->guest->range('0');
$zd_company->gen(1);
$dbh->exec("UPDATE zt_company SET admins = ',admin,' WHERE id = 1");
global $app;
$app->company = $dbh->query('SELECT * FROM zt_company WHERE id = 1')->fetch(PDO::FETCH_OBJ);

su('admin');
$zd_entry = zenData('entry');
$zd_entry->id->range('1');
$zd_entry->name->range('GitFox');
$zd_entry->account->range('admin');
$zd_entry->code->range('gitfox');
$zd_entry->key->range('gitfox');
$zd_entry->freePasswd->range('1');
$zd_entry->ip->range('*');
$zd_entry->createdBy->range('admin');
$zd_entry->createdDate->range('`2026-01-01 00:00:00`');
$zd_entry->calledTime->range('0');
$zd_entry->editedBy->range('admin');
$zd_entry->editedDate->range('`2026-01-01 00:00:00`');
$zd_entry->deleted->range('0');
$zd_entry->gen(1);



$tester->loadModel('repobranchtype');

/**

title=测试 repobranchtypeModel::apiCreateBranchType()
timeout=0
cid=0

- 步骤1：正常输入 @daoError:{"apiMessage":"Body 参数解析失败。"}
- 步骤2：边界值输入 @daoError:{"apiMessage":"Body 参数解析失败。"}
- 步骤3：无效输入 @daoError:{"apiMessage":"Path 参数解析失败。"}
- 步骤4：大值输入 @daoError:{"apiMessage":"Body 参数解析失败。"}
- 步骤5：业务规则验证 @daoError:{"apiMessage":"Body 参数解析失败。"}

*/

r(ztfVal(ztfCall(function() use ($tester) { return $tester->repobranchtype->apiCreateBranchType(1, (object)array()); }))) && p() && e('daoError:{"apiMessage":"Body 参数解析失败。"}'); // 步骤1：正常输入
r(ztfVal(ztfCall(function() use ($tester) { return $tester->repobranchtype->apiCreateBranchType(0, (object)array()); }))) && p() && e('daoError:{"apiMessage":"Body 参数解析失败。"}'); // 步骤2：边界值输入
r(ztfVal(ztfCall(function() use ($tester) { return $tester->repobranchtype->apiCreateBranchType(-1, (object)array()); }))) && p() && e('daoError:{"apiMessage":"Path 参数解析失败。"}'); // 步骤3：无效输入
r(ztfVal(ztfCall(function() use ($tester) { return $tester->repobranchtype->apiCreateBranchType(999999, (object)array()); }))) && p() && e('daoError:{"apiMessage":"Body 参数解析失败。"}'); // 步骤4：大值输入
r(ztfVal(ztfCall(function() use ($tester) { return $tester->repobranchtype->apiCreateBranchType(2, (object)array('id' => 1, 'name' => 'test')); }))) && p() && e('daoError:{"apiMessage":"Body 参数解析失败。"}'); // 步骤5：业务规则验证
