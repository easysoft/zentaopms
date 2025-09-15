#!/usr/bin/env php
<?php

/**

title=测试 productZen::responseNotFound4View();
timeout=0
cid=0

- 步骤1：API模式返回失败状态属性status @fail
- 步骤2：非API模式返回成功结果属性result @success
- 步骤3：API模式返回404错误码属性code @404
- 步骤4：非API模式跳转地址第load条的locate属性 @/zentao/product-all.html
- 步骤5：API模式错误消息属性message @404 Not found

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$productTest = new productTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($productTest->responseNotFound4ViewTest('api')) && p('status') && e('fail'); // 步骤1：API模式返回失败状态
r($productTest->responseNotFound4ViewTest('normal')) && p('result') && e('success'); // 步骤2：非API模式返回成功结果
r($productTest->responseNotFound4ViewTest('api')) && p('code') && e(404); // 步骤3：API模式返回404错误码
r($productTest->responseNotFound4ViewTest('normal')) && p('load:locate') && e('/zentao/product-all.html'); // 步骤4：非API模式跳转地址
r($productTest->responseNotFound4ViewTest('api')) && p('message') && e('404 Not found'); // 步骤5：API模式错误消息