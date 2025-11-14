#!/usr/bin/env php
<?php

/**

title=测试 ciModel::saveCompile();
timeout=0
cid=15589

- 测试404响应处理属性result @1
- 测试XML响应解析属性status @created
- 测试JSON executable响应属性result @1
- 测试notFound响应处理属性result @1
- 测试building状态响应属性result @1
- 测试SUCCESS状态响应属性result @1
- 测试FAILURE状态响应属性result @1
- 测试无效JSON响应属性result @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ci.unittest.class.php';

zenData('pipeline')->gen(3);
zenData('job')->loadYaml('job')->gen(5);
zenData('compile')->loadYaml('compile')->gen(8);

su('admin');

$response404 = '404 Not Found';
$responseXml = 'status 404<xml><build><number>95</number><result>SUCCESS</result><queueId>1001</queueId></build></xml>';
$responseJson = '{"executable":{"url":"http://test.jenkins.com/job/test/95/"}}';
$responseNotFound = 'Build not found';
$responseBuilding = '{"building":true,"result":null,"url":"http://test.jenkins.com/job/test/95/"}';
$responseSuccess = '{"building":false,"result":"SUCCESS","url":"http://test.jenkins.com/job/test/95/"}';
$responseFailure = '{"building":false,"result":"FAILURE","url":"http://test.jenkins.com/job/test/95/"}';
$responseInvalid = 'invalid json content';

libxml_use_internal_errors(true);
$ci = new ciTest();
r($ci->saveCompileTest(1, $response404)) && p('result') && e(1); // 测试404响应处理
r($ci->saveCompileTest(2, $responseXml)) && p('status') && e('created'); // 测试XML响应解析
r($ci->saveCompileTest(3, $responseJson)) && p('result') && e(1); // 测试JSON executable响应
r($ci->saveCompileTest(4, $responseNotFound)) && p('result') && e(1); // 测试notFound响应处理
r($ci->saveCompileTest(5, $responseBuilding)) && p('result') && e(1); // 测试building状态响应
r($ci->saveCompileTest(1, $responseSuccess)) && p('result') && e(1); // 测试SUCCESS状态响应
r($ci->saveCompileTest(2, $responseFailure)) && p('result') && e(1); // 测试FAILURE状态响应
r($ci->saveCompileTest(3, $responseInvalid)) && p('result') && e(1); // 测试无效JSON响应