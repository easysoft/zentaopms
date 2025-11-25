#!/usr/bin/env php
<?php

/**

title=测试 commonModel::judgeSuhosinSetting();
timeout=0
cid=15685

false
false
true
false
true


*/

// 最小化的框架类定义，仅支持测试judgeSuhosinSetting方法
class model {}

// 加载commonModel类定义
require_once dirname(__FILE__, 3) . '/model.php';

// 使用反射调用static方法
function callJudgeSuhosinSetting($countInputVars) {
    return commonModel::judgeSuhosinSetting($countInputVars);
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
    if(is_bool($_result)) {
        echo $_result ? 'true' : 'false';
        echo "\n";
    } else {
        echo (string) $_result . "\n";
    }
    return true;
}

function e($expect)
{
    // 简化的期望值处理
    return true;
}

// 4. 强制要求：必须包含至少5个测试步骤
r(callJudgeSuhosinSetting(100)) && p() && e('false'); // 步骤1：正常小数值输入
r(callJudgeSuhosinSetting(1000)) && p() && e('false'); // 步骤2：边界值测试
r(callJudgeSuhosinSetting(100000)) && p() && e('true'); // 步骤3：超过默认限制
r(callJudgeSuhosinSetting(0)) && p() && e('false'); // 步骤4：零值边界测试
r(callJudgeSuhosinSetting(50000)) && p() && e('true'); // 步骤5：大幅超过限制