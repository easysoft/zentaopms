#!/usr/bin/env php
<?php

/**

title=测试 cneModel::apiGet();
timeout=0
cid=15599

- 测试步骤1:正常的API GET请求属性code @200
- 测试步骤2:带查询参数的API GET请求第data条的name属性 @my-app
- 测试步骤3:API返回404错误的情况属性code @404
- 测试步骤4:API返回401认证错误的情况属性code @401
- 测试步骤5:使用自定义host的API GET请求属性code @200

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

$cneTest = new cneTest();

r($cneTest->apiGetTest('/api/cne/app/status', array('name' => 'test-app'), array(), '')) && p('code') && e('200'); // 测试步骤1:正常的API GET请求
r($cneTest->apiGetTest('/api/cne/app/info', array('name' => 'my-app'), array(), '')) && p('data:name') && e('my-app'); // 测试步骤2:带查询参数的API GET请求
r($cneTest->apiGetTest('/api/cne/app/error', array(), array(), '')) && p('code') && e('404'); // 测试步骤3:API返回404错误的情况
r($cneTest->apiGetTest('/api/cne/app/auth-error', array(), array(), '')) && p('code') && e('401'); // 测试步骤4:API返回401认证错误的情况
r($cneTest->apiGetTest('/api/cne/app/custom-host', array(), array(), 'http://custom-host')) && p('code') && e('200'); // 测试步骤5:使用自定义host的API GET请求