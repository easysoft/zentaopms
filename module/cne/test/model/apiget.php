#!/usr/bin/env php
<?php

/**

title=测试 cneModel::apiGet();
timeout=0
cid=0

- 步骤1：正常GET请求属性code @200
- 步骤2：带数组参数的请求属性data @my-app
属性name @my-app
- 步骤3：带对象参数的请求属性data @obj-app
属性name @obj-app
- 步骤4：API错误响应属性code @404
- 步骤5：认证错误响应属性code @401
- 步骤6：自定义host属性data @http://custom.host
属性host @http://custom.host
- 步骤7：服务器错误属性code @600

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$cneTest = new cneTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($cneTest->apiGetTest('/api/cne/app/status', array('name' => 'test-app'))) && p('code') && e('200'); // 步骤1：正常GET请求
r($cneTest->apiGetTest('/api/cne/app/info', array('name' => 'my-app', 'namespace' => 'default'))) && p('data,name') && e('my-app'); // 步骤2：带数组参数的请求
r($cneTest->apiGetTest('/api/cne/app/info', (object)array('name' => 'obj-app'))) && p('data,name') && e('obj-app'); // 步骤3：带对象参数的请求
r($cneTest->apiGetTest('/api/cne/app/error', array())) && p('code') && e('404'); // 步骤4：API错误响应
r($cneTest->apiGetTest('/api/cne/app/auth-error', array())) && p('code') && e('401'); // 步骤5：认证错误响应
r($cneTest->apiGetTest('/api/cne/app/custom-host', array(), array(), 'http://custom.host')) && p('data,host') && e('http://custom.host'); // 步骤6：自定义host
r($cneTest->apiGetTest('/invalid-url', array())) && p('code') && e('600'); // 步骤7：服务器错误