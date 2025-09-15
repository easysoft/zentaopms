#!/usr/bin/env php
<?php

/**

title=测试 commonModel::http();
timeout=0
cid=0

- 步骤1：正常GET请求 @GET response data
- 步骤2：正常POST请求带数据 @POST response with data
- 步骤3：JSON数据类型请求 @{"status":"success","data":"test"}

- 步骤4：带HTTP状态码的响应属性1 @201
- 步骤5：无效URL输入 @alse
- 步骤6：PUT方法请求 @PUT response
- 步骤7：错误URL请求 @alse

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

su('admin');

$commonTest = new commonTest();

r($commonTest->httpTest('https://example.com/api', null, array(), array(), 'data', 'GET')) && p() && e('GET response data'); // 步骤1：正常GET请求
r($commonTest->httpTest('https://example.com/api', array('name' => 'test'), array(), array(), 'data', 'POST')) && p() && e('POST response with data'); // 步骤2：正常POST请求带数据
r($commonTest->httpTest('https://example.com/json/api', array('data' => 'test'), array(), array(), 'json', 'POST')) && p() && e('{"status":"success","data":"test"}'); // 步骤3：JSON数据类型请求
r($commonTest->httpTest('https://example.com/httpcode/api', null, array(), array(), 'data', 'POST', 30, true)) && p('1') && e('201'); // 步骤4：带HTTP状态码的响应
r($commonTest->httpTest('', null, array(), array(), 'data', 'POST')) && p() && e(false); // 步骤5：无效URL输入
r($commonTest->httpTest('https://example.com/api', array('update' => 'data'), array(), array(), 'data', 'PUT')) && p() && e('PUT response'); // 步骤6：PUT方法请求
r($commonTest->httpTest('https://example.com/error/api', null, array(), array(), 'data', 'GET')) && p() && e(false); // 步骤7：错误URL请求