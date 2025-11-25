#!/usr/bin/env php
<?php

/**

title=测试 cneModel::apiPost();
timeout=0
cid=15600

- 测试步骤1:正常的API POST请求属性code @200
- 测试步骤2:使用对象数据的POST请求第data条的name属性 @new-app
- 测试步骤3:API返回400错误的情况属性code @400
- 测试步骤4:网络错误的情况属性code @600
- 测试步骤5:使用自定义host的POST请求属性code @200

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

$cneTest = new cneTest();

r($cneTest->apiPostTest('/api/cne/app/install', array('name' => 'test-app'), array(), '')) && p('code') && e('200'); // 测试步骤1:正常的API POST请求
r($cneTest->apiPostTest('/api/cne/app/create', (object)array('name' => 'new-app'), array(), '')) && p('data:name') && e('new-app'); // 测试步骤2:使用对象数据的POST请求
r($cneTest->apiPostTest('/api/cne/app/error', array(), array(), '')) && p('code') && e('400'); // 测试步骤3:API返回400错误的情况
r($cneTest->apiPostTest('/api/cne/app/network-error', array(), array(), '')) && p('code') && e('600'); // 测试步骤4:网络错误的情况
r($cneTest->apiPostTest('/api/cne/app/custom-host', array(), array(), 'http://custom-host')) && p('code') && e('200'); // 测试步骤5:使用自定义host的POST请求