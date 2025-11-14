#!/usr/bin/env php
<?php

/**

title=测试 commonModel::apiDelete();
timeout=0
cid=15639

- 步骤1：成功删除请求属性code @200
- 步骤2：业务错误请求属性code @400
- 步骤3：空URL参数 @Empty URL
- 步骤4：超时错误请求属性code @600
- 步骤5：无效JSON响应属性code @600

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$commonTest = new commonTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($commonTest->apiDeleteTest('https://api.example.com/success', array('id' => 123))) && p('code') && e('200'); // 步骤1：成功删除请求
r($commonTest->apiDeleteTest('https://api.example.com/error', array('id' => 456))) && p('code') && e('400'); // 步骤2：业务错误请求
r($commonTest->apiDeleteTest('', array('id' => 789))) && p() && e('Empty URL'); // 步骤3：空URL参数
r($commonTest->apiDeleteTest('https://api.example.com/timeout', array('id' => 999))) && p('code') && e('600'); // 步骤4：超时错误请求
r($commonTest->apiDeleteTest('https://api.example.com/invalid-json', array('id' => 111))) && p('code') && e('600'); // 步骤5：无效JSON响应