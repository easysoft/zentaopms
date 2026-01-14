#!/usr/bin/env php
<?php

/**

title=测试 commonModel::apiPut();
timeout=0
cid=15643

- 步骤1：正常成功请求属性code @200
- 步骤2：业务错误请求属性code @400
- 步骤3：无效URL属性code @600
- 步骤4：空URL @Empty URL
- 步骤5：带headers的请求属性code @200

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$commonTest = new commonModelTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($commonTest->apiPutTest('https://api.example.com/success', array('key' => 'value'))) && p('code') && e('200'); // 步骤1：正常成功请求
r($commonTest->apiPutTest('https://api.example.com/error', array('key' => 'value'))) && p('code') && e('400'); // 步骤2：业务错误请求
r($commonTest->apiPutTest('invalid-url', array('key' => 'value'))) && p('code') && e('600'); // 步骤3：无效URL
r($commonTest->apiPutTest('', array('key' => 'value'))) && p() && e('Empty URL'); // 步骤4：空URL
r($commonTest->apiPutTest('https://api.example.com/success', array('data' => 'test'), array('Content-Type: application/json'))) && p('code') && e('200'); // 步骤5：带headers的请求