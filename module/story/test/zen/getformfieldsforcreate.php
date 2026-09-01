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

$zd_product = zenData('product');
$zd_product->id->range('1-5');
$zd_product->name->range('产品1,产品2,产品3,产品4,产品5');
$zd_product->status->range('normal');
$zd_product->type->range('normal');
$zd_product->deleted->range('0');
$zd_product->gen(5);

$zd_project = zenData('project');
$zd_project->id->range('1-5');
$zd_project->name->prefix('项目')->range('1-5');
$zd_project->type->range('project');
$zd_project->status->range('doing');
$zd_project->deleted->range('0');
$zd_project->gen(5);

$zd_projectproduct = zenData('projectproduct');
$zd_projectproduct->id->range('1-5');
$zd_projectproduct->project->range('1-5');
$zd_projectproduct->product->range('1-5');
$zd_projectproduct->branch->range('0');
$zd_projectproduct->gen(5);

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

$tester->loadModel('story');
$tester->app->setModuleName('story');
helper::import($tester->app->getModulePath('', 'story') . 'control.php');
helper::import($tester->app->getModulePath('', 'story') . 'zen.php');

/**

title=测试 storyModel::getFormFieldsForCreate()
timeout=0
cid=0

- 步骤1：正常输入 @array_count=30
- 步骤2：边界值输入 @array_count=30
- 步骤3：无效输入 @array_count=30
- 步骤4：大值输入 @array_count=30
- 步骤5：业务规则验证 @array_count=30

*/

r(ztfVal(ztfCall(function() use ($tester) { return callZenMethod('story', 'getFormFieldsForCreate', array(1, '1', 1, (object)array(), 'story')); }))) && p() && e('array_count=30'); // 步骤1：正常输入
r(ztfVal(ztfCall(function() use ($tester) { return callZenMethod('story', 'getFormFieldsForCreate', array(0, '1', 1, (object)array(), 'story')); }))) && p() && e('array_count=30'); // 步骤2：边界值输入
r(ztfVal(ztfCall(function() use ($tester) { return callZenMethod('story', 'getFormFieldsForCreate', array(-1, '1', 1, (object)array(), 'story')); }))) && p() && e('array_count=30'); // 步骤3：无效输入
r(ztfVal(ztfCall(function() use ($tester) { return callZenMethod('story', 'getFormFieldsForCreate', array(999999, '1', 1, (object)array(), 'story')); }))) && p() && e('array_count=30'); // 步骤4：大值输入
r(ztfVal(ztfCall(function() use ($tester) { return callZenMethod('story', 'getFormFieldsForCreate', array(2, 'test', 1, (object)array(), 'story')); }))) && p() && e('array_count=30'); // 步骤5：业务规则验证
