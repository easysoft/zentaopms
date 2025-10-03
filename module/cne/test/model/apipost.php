#!/usr/bin/env php
<?php

/**

title=测试 cneModel::apiPost();
timeout=0
cid=0

- 步骤1：正常情况
 - 属性code @200
 - 属性message @success
- 步骤2：边界值测试（空URL）
 - 属性code @600
 - 属性message @CNE服务器出错
- 步骤3：自定义参数测试第data条的host属性 @http://custom-host:8080
- 步骤4：异常处理测试
 - 属性code @400
 - 属性message @Bad request
- 步骤5：网络错误测试
 - 属性code @600
 - 属性message @CNE服务器出错

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

$cneTest = new cneTest();

r($cneTest->apiPostTest('/api/cne/app/install', array('name' => 'test-app', 'namespace' => 'default'))) && p('code,message') && e('200,success'); // 步骤1：正常情况
r($cneTest->apiPostTest('', array())) && p('code,message') && e('600,CNE服务器出错'); // 步骤2：边界值测试（空URL）
r($cneTest->apiPostTest('/api/cne/app/custom-host', array(), array(), 'http://custom-host:8080')) && p('data:host') && e('http://custom-host:8080'); // 步骤3：自定义参数测试
r($cneTest->apiPostTest('/api/cne/app/error', array('invalid' => 'data'))) && p('code,message') && e('400,Bad request'); // 步骤4：异常处理测试
r($cneTest->apiPostTest('/api/cne/app/network-error', array())) && p('code,message') && e('600,CNE服务器出错'); // 步骤5：网络错误测试