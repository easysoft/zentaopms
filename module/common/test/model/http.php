#!/usr/bin/env php
<?php

/**

title=测试 commonModel::http();
timeout=0
cid=15681

- 执行commonTest模块的httpTest方法，参数是'https://example.com/api', null, array  @GET response data
- 执行commonTest模块的httpTest方法，参数是'https://example.com/api', array  @POST response with data
- 执行commonTest模块的httpTest方法，参数是'https://example.com/json/api', array  @{"status":"success","data":"test"}

- 执行commonTest模块的httpTest方法，参数是'https://example.com/httpcode/api', null, array 属性1 @201
- 执行commonTest模块的httpTest方法，参数是'', null, array  @0
- 执行commonTest模块的httpTest方法，参数是'https://example.com/api', array  @PUT response
- 执行commonTest模块的httpTest方法，参数是'https://example.com/error/api', null, array  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$commonTest = new commonModelTest();

r($commonTest->httpTest('https://example.com/api', null, array(), array(), 'data', 'GET')) && p() && e('GET response data');
r($commonTest->httpTest('https://example.com/api', array('name' => 'test'), array(), array(), 'data', 'POST')) && p() && e('POST response with data');
r($commonTest->httpTest('https://example.com/json/api', array('data' => 'test'), array(), array(), 'json', 'POST')) && p() && e('{"status":"success","data":"test"}');
r($commonTest->httpTest('https://example.com/httpcode/api', null, array(), array(), 'data', 'POST', 30, true)) && p('1') && e('201');
r($commonTest->httpTest('', null, array(), array(), 'data', 'POST')) && p() && e('0');
r($commonTest->httpTest('https://example.com/api', array('update' => 'data'), array(), array(), 'data', 'PUT')) && p() && e('PUT response');
r($commonTest->httpTest('https://example.com/error/api', null, array(), array(), 'data', 'GET')) && p() && e('0');