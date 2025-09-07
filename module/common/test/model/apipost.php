#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 commonModel::apiPost();
timeout=0
cid=0

- 测试步骤1：正常POST请求属性code @200
- 测试步骤2：成功响应
 - 属性code @200
 - 属性message @Success
- 测试步骤3：业务错误响应
 - 属性code @400
 - 属性message @Bad Request
- 测试步骤4：空URL验证 @Empty URL
- 测试步骤5：无效URL格式属性code @600
- 测试步骤6：超时错误
 - 属性code @600
 - 属性message @HTTP Server Error
- 测试步骤7：复杂数据结构
 - 第code,data条的user:id属性 @200

*/

include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

$commonTest = new commonTest();

r($commonTest->apiPostTest('https://api.example.com/test', array('key' => 'value'))) && p('code') && e(200); // 测试步骤1：正常POST请求
r($commonTest->apiPostTest('https://api.example.com/success', array('data' => 'test'))) && p('code,message') && e('200,Success'); // 测试步骤2：成功响应
r($commonTest->apiPostTest('https://api.example.com/error', array('action' => 'fail'))) && p('code,message') && e('400,Bad Request'); // 测试步骤3：业务错误响应
r($commonTest->apiPostTest('', array('empty' => 'url'))) && p() && e('Empty URL'); // 测试步骤4：空URL验证
r($commonTest->apiPostTest('invalid-url', array('test' => 'data'))) && p('code') && e(600); // 测试步骤5：无效URL格式
r($commonTest->apiPostTest('https://api.example.com/timeout', array('slow' => 'request'))) && p('code,message') && e('600,HTTP Server Error'); // 测试步骤6：超时错误
r($commonTest->apiPostTest('https://api.example.com/complex', array('user' => array('id' => 1, 'name' => 'test'), 'nested' => array('level1' => array('level2' => 'value'))))) && p('code,data:user:id') && e('200,1'); // 测试步骤7：复杂数据结构