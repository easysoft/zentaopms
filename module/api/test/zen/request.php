#!/usr/bin/env php
<?php

/**

title=测试 apiZen::request();
timeout=0
cid=15127

- 执行apiTest模块的requestTest方法，参数是'user', 'getById', 'extendModel', array 属性content @mock_response_content
- 执行apiTest模块的requestTest方法，参数是'user', 'getList', 'extendModel', array 属性content @mock_response_content
- 执行apiTest模块的requestTest方法，参数是'product', 'browse', 'extendControl', array 属性content @mock_response_content
- 执行apiTest模块的requestTest方法，参数是'task', 'browse', 'extendControl', array 属性content @mock_response_content
- 执行apiTest模块的requestTest方法，参数是'', 'getById', 'extendModel', array 属性content @mock_response_content

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$apiTest = new apiZenTest();

r($apiTest->requestTest('user', 'getById', 'extendModel', array('userID' => '1', 'account' => 'admin'))) && p('content') && e('mock_response_content');
r($apiTest->requestTest('user', 'getList', 'extendModel', array('noparam' => true))) && p('content') && e('mock_response_content');
r($apiTest->requestTest('product', 'browse', 'extendControl', array('productID' => '1', 'branch' => '0'))) && p('content') && e('mock_response_content');
r($apiTest->requestTest('task', 'browse', 'extendControl', array('noparam' => true))) && p('content') && e('mock_response_content');
r($apiTest->requestTest('', 'getById', 'extendModel', array('userID' => '1'))) && p('content') && e('mock_response_content');