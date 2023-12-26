#!/usr/bin/env php
<?php

/**

title=测试 ciModel->saveCompile();
timeout=0
cid=1

- 错误的接口信息 @1
- 没有有效信息 @0
- 返回请求URL信息 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/ci.class.php';

zdTable('pipeline')->gen(3);
zdTable('job')->config('job')->gen(5);
zdTable('compile')->config('compile')->gen(5);
zdTable('mr')->gen(0);
su('admin');

$response = '{"_class":"hudson.model.Queue$LeftItem","actions":[{"_class":"hudson.model.ParametersAction","parameters":[{"_class":"hudson.model.StringParameterValue","name":"PARAM_TAG","value":"zentaopms_18.3"}]},{"_class":"hudson.model.CauseAction","causes":[{"_class":"hudson.model.Cause$UserIdCause","shortDescription":"Started by user admin","userId":"admin","userName":"admin"}]}],"blocked":false,"buildable":false,"id":9687,"inQueueSince":1703571695675,"params":"\u000aPARAM_TAG=zentaopms_18.3","stuck":false,"task":{"_class":"hudson.model.FreeStyleProject","name":"sonarqube_job","url":"http://10.0.7.242:9580/job/sonarqube_job/","color":"blue"},"url":"queue/item/9687/","why":null,"cancelled":false,"executable":{"_class":"hudson.model.FreeStyleBuild","number":95,"url":"http://10.0.7.242:9580/job/sonarqube_job/95/"}}';
$hasUrl   = '{"executable":{"url":"https://jenkinsdev.qc.oop.cc/job/paramsJob/lastBuild/"}}';
$notFound = '404';

$ci = new ciTest();
r($ci->saveCompileTest(1, $notFound)) && p() && e('1'); // 错误的接口信息

r($ci->saveCompileTest(3, $response)) && p() && e('0'); // 没有有效信息

r($ci->saveCompileTest(5, $hasUrl))   && p() && e('1'); // 返回请求URL信息