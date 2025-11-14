#!/usr/bin/env php
<?php

/**

title=测试 apiModel::createDemoLib();
timeout=0
cid=15094

- 执行apiTest模块的createDemoLibTest方法，参数是'API Demo Library', 'https://api.demo.com', 'admin' 属性name @API Demo Library
- 执行apiTest模块的createDemoLibTest方法，参数是'API Demo Library', 'https://api.demo.com', 'admin' 属性type @api
- 执行apiTest模块的createDemoLibTest方法，参数是'API Demo Library', 'https://api.demo.com', 'admin' 属性baseUrl @https://api.demo.com
- 执行apiTest模块的createDemoLibTest方法，参数是'API Demo Library', 'https://api.demo.com', 'admin' 属性acl @open
- 执行apiTest模块的createDemoLibTest方法，参数是'Test Library', '', 'admin' 属性baseUrl @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/api.unittest.class.php';

zenData('doclib');
zenData('user');

su('admin');

$apiTest = new apiTest();

r($apiTest->createDemoLibTest('API Demo Library', 'https://api.demo.com', 'admin')) && p('name') && e('API Demo Library');
r($apiTest->createDemoLibTest('API Demo Library', 'https://api.demo.com', 'admin')) && p('type') && e('api');
r($apiTest->createDemoLibTest('API Demo Library', 'https://api.demo.com', 'admin')) && p('baseUrl') && e('https://api.demo.com');
r($apiTest->createDemoLibTest('API Demo Library', 'https://api.demo.com', 'admin')) && p('acl') && e('open');
r($apiTest->createDemoLibTest('Test Library', '', 'admin')) && p('baseUrl') && e('~~');