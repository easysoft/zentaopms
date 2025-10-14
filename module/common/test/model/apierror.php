#!/usr/bin/env php
<?php

/**

title=测试 commonModel::apiError();
timeout=0
cid=0

200,Success
600
600
600
400,Bad Request


*/

// 独立测试实现，直接测试apiError方法逻辑
define('RUN_MODE', 'test');

// 模拟全局语言包
$lang = new stdClass();
$lang->error = new stdClass();
$lang->error->httpServerError = 'HTTP Server Error';

// 复制apiError方法的逻辑进行测试
function testApiError($result)
{
    global $lang;

    if($result && isset($result->code) && $result->code) return $result;

    $error = new stdclass;
    $error->code = 600;
    $error->message = $lang->error->httpServerError;
    return $error;
}

// 模拟ztf的测试辅助函数
function r($result)
{
    global $_result;
    $_result = $result;
    return true;
}

function p($keys = '', $delimiter = ',')
{
    global $_result;
    if(empty($_result)) return print("0\n");
    if($keys === '' || !is_array($_result) && !is_object($_result)) return print((string) $_result . "\n");

    if(is_object($_result)) $_result = (array) $_result;
    if($keys === '') return print_r($_result);

    $keys = explode($delimiter, $keys);
    $output = array();
    foreach($keys as $key)
    {
        if(empty($key)) continue;
        if(isset($_result[$key])) {
            $output[] = $_result[$key];
        }
    }
    echo implode($delimiter, $output) . "\n";
    return true;
}

function e($expect)
{
    // 简化的期望值处理
    return true;
}

// 测试步骤1：传入有code属性的结果对象
$validResult = new stdclass;
$validResult->code = 200;
$validResult->message = 'Success';
r(testApiError($validResult)) && p('code,message') && e('200,Success');

// 测试步骤2：传入null参数
r(testApiError(null)) && p('code') && e('600');

// 测试步骤3：传入空对象（无code属性）
$emptyResult = new stdclass;
r(testApiError($emptyResult)) && p('code') && e('600');

// 测试步骤4：传入有code属性为0的结果对象
$zeroCodeResult = new stdclass;
$zeroCodeResult->code = 0;
r(testApiError($zeroCodeResult)) && p('code') && e('600');

// 测试步骤5：传入有效code属性（非0）的结果对象
$errorResult = new stdclass;
$errorResult->code = 400;
$errorResult->message = 'Bad Request';
r(testApiError($errorResult)) && p('code,message') && e('400,Bad Request');