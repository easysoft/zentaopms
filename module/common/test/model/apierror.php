#!/usr/bin/env php
<?php

/**

title=测试 commonModel::apiError();
timeout=0
cid=0

- 执行commonTest模块的apiErrorTest方法，参数是$validResult 
 - 属性code @200
 - 属性message @Success
- 执行commonTest模块的apiErrorTest方法，参数是null 属性code @600
- 执行commonTest模块的apiErrorTest方法，参数是$emptyResult 属性code @600
- 执行commonTest模块的apiErrorTest方法，参数是$zeroCodeResult 属性code @600
- 执行commonTest模块的apiErrorTest方法，参数是$errorResult 
 - 属性code @400
 - 属性message @Bad Request

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$commonTest = new commonTest();

// 4. 强制要求：必须包含至少5个测试步骤

// 测试步骤1：传入有code属性的结果对象，期望返回原始结果对象
$validResult = new stdclass;
$validResult->code = 200;
$validResult->message = 'Success';
r($commonTest->apiErrorTest($validResult)) && p('code,message') && e('200,Success');

// 测试步骤2：传入null参数，期望返回默认错误对象，code为600
r($commonTest->apiErrorTest(null)) && p('code') && e('600');

// 测试步骤3：传入空对象（无code属性），期望返回默认错误对象，code为600
$emptyResult = new stdclass;
r($commonTest->apiErrorTest($emptyResult)) && p('code') && e('600');

// 测试步骤4：传入有code属性为0的结果对象，期望返回默认错误对象，code为600
$zeroCodeResult = new stdclass;
$zeroCodeResult->code = 0;
r($commonTest->apiErrorTest($zeroCodeResult)) && p('code') && e('600');

// 测试步骤5：传入有效code属性（非0）的结果对象，期望返回原始结果对象
$errorResult = new stdclass;
$errorResult->code = 400;
$errorResult->message = 'Bad Request';
r($commonTest->apiErrorTest($errorResult)) && p('code,message') && e('400,Bad Request');