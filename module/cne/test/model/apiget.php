#!/usr/bin/env php
<?php

/**

title=测试 cneModel::apiGet();
timeout=0
cid=15599

- 测试正常GET请求：有效的URL和参数属性code @600
- 测试空数据参数：验证空数组处理属性code @600
- 测试带已有查询参数的URL属性code @600
- 测试自定义header参数属性code @600
- 测试自定义host参数属性code @600

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$cneTest = new cneModelTest();

r($cneTest->apiGetTest('/api/cne/test', array('param1' => 'value1'), array(), '')) && p('code') && e('600');  // 测试正常GET请求：有效的URL和参数
r($cneTest->apiGetTest('/api/cne/test', array(), array(), '')) && p('code') && e('600');  // 测试空数据参数：验证空数组处理
r($cneTest->apiGetTest('/api/cne/test?existing=param', array('new' => 'param'), array(), '')) && p('code') && e('600');  // 测试带已有查询参数的URL
r($cneTest->apiGetTest('/api/cne/test', array('param' => 'value'), array('Authorization: Bearer token'), '')) && p('code') && e('600');  // 测试自定义header参数
r($cneTest->apiGetTest('/api/cne/test', array('param' => 'value'), array(), 'http://custom-host.com')) && p('code') && e('600');  // 测试自定义host参数