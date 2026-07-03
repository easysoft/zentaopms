#!/usr/bin/env php
<?php

/**

title=测试 aiModel::http();
timeout=0
cid=0

- 步骤1：GET 请求成功返回响应体 @{"result":"get-success"}
- 步骤2：POST 带 JSON 数据成功返回 @{"result":"post-success","data":{"name":"test"}}

- 步骤3：PUT 自定义请求方法成功返回 @{"result":"put-success"}
- 步骤4：curl 连接失败时返回 false @0
- 步骤5：HTTP 状态码异常时返回 false @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$aiTest = new aiModelTest();

/* 步骤1：GET 请求成功返回响应体 */
r($aiTest->httpTest('GET', 'http://mock.test/get-success')) && p() && e('{"result":"get-success"}'); // 步骤1：GET 请求成功返回响应体

/* 步骤2：POST 带 JSON 数据成功返回 */
r($aiTest->httpTest('post', 'http://mock.test/post-success', array('name' => 'test'))) && p() && e('{"result":"post-success","data":{"name":"test"}}'); // 步骤2：POST 带 JSON 数据成功返回

/* 步骤3：PUT 自定义请求方法成功返回 */
r($aiTest->httpTest('PUT', 'http://mock.test/put-success')) && p() && e('{"result":"put-success"}'); // 步骤3：PUT 自定义请求方法成功返回

/* 步骤4：curl 连接失败时返回 false */
r($aiTest->httpTest('GET', 'http://mock.test/curl-error')) && p() && e('0'); // 步骤4：curl 连接失败时返回 false

/* 步骤5：HTTP 状态码异常时返回 false */
r($aiTest->httpTest('GET', 'http://mock.test/http-error')) && p() && e('0'); // 步骤5：HTTP 状态码异常时返回 false