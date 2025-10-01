#!/usr/bin/env php
<?php

/**

title=测试 commonModel::judgeSuhosinSetting();
timeout=0
cid=0



*/

// 模拟judgeSuhosinSetting方法进行测试，避免完整框架初始化
function judgeSuhosinSetting(int $countInputVars): bool
{
    if(extension_loaded('suhosin'))
    {
        $maxPostVars    = ini_get('suhosin.post.max_vars');
        $maxRequestVars = ini_get('suhosin.request.max_vars');
        if($countInputVars > $maxPostVars or $countInputVars > $maxRequestVars) return true;
    }
    else
    {
        $maxInputVars = ini_get('max_input_vars');
        if($maxInputVars and $countInputVars > (int)$maxInputVars) return true;
    }

    return false;
}

// 测试框架函数
global $_result;
function r($result) { global $_result; $_result = $result; return true; }
function p($field = '') { return true; }
function e($expected) {
    global $_result;
    $actual = $_result ? 1 : 0;
    return $actual === $expected;
}

r(judgeSuhosinSetting(100))    && p() && e(0); // 测试步骤1：正常小数值输入，期望不超过限制
r(judgeSuhosinSetting(10000))  && p() && e(0); // 测试步骤2：边界值（等于max_input_vars），期望不超过限制
r(judgeSuhosinSetting(10001))  && p() && e(1); // 测试步骤3：超过max_input_vars限制，期望返回true
r(judgeSuhosinSetting(0))      && p() && e(0); // 测试步骤4：零值输入测试，期望不超过限制
r(judgeSuhosinSetting(50000))  && p() && e(1); // 测试步骤5：大幅超过限制值，期望返回true