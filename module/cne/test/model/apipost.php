#!/usr/bin/env php
<?php

/**

title=测试 cneModel::apiPost();
timeout=0
cid=0

- 执行cneTest模块的apiPostTest方法，参数是'/api/cne/app/install', array 
 - 属性code @200
 - 属性message @success
- 执行cneTest模块的apiPostTest方法，参数是'/api/cne/app/create',  属性code @200
- 执行cneTest模块的apiPostTest方法，参数是'/api/cne/app/custom-host', array 第data条的host属性 @http://custom-host:8080
- 执行cneTest模块的apiPostTest方法，参数是'/api/cne/app/custom-host', array 第data条的method属性 @POST
- 执行cneTest模块的apiPostTest方法，参数是'/api/cne/app/error', array 
 - 属性code @400
 - 属性message @Bad request
- 执行cneTest模块的apiPostTest方法，参数是'/api/cne/app/network-error', array 
 - 属性code @600
 - 属性message @CNE服务器错误

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

$cneTest = new cneTest();

r($cneTest->apiPostTest('/api/cne/app/install', array('name' => 'test-app', 'namespace' => 'default'))) && p('code,message') && e('200,success');
r($cneTest->apiPostTest('/api/cne/app/create', (object)array('name' => 'new-app'))) && p('code') && e('200');
r($cneTest->apiPostTest('/api/cne/app/custom-host', array(), array(), 'http://custom-host:8080')) && p('data:host') && e('http://custom-host:8080');
r($cneTest->apiPostTest('/api/cne/app/custom-host', array(), array(), 'http://custom-host:8080')) && p('data:method') && e('POST');
r($cneTest->apiPostTest('/api/cne/app/error', array('invalid' => 'data'))) && p('code,message') && e('400,Bad request');
r($cneTest->apiPostTest('/api/cne/app/network-error', array())) && p('code,message') && e('600,CNE服务器错误');