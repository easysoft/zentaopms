#!/usr/bin/env php
<?php

/**

title=测试 commonModel::apiPost();
cid=15642

- 测试步骤1：正常POST请求 >> 期望返回code为200
- 测试步骤2：成功响应检查 >> 期望返回code为200且message为Success
- 测试步骤3：业务错误响应 >> 期望返回code为400且message为Bad Request
- 测试步骤4：空URL验证 >> 期望返回Empty URL错误信息
- 测试步骤5：无效URL格式验证 >> 期望返回code为600的错误

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

su('admin');

$commonTest = new commonTest();

r($commonTest->apiPostTest('https://api.example.com/test', array('key' => 'value'))) && p('code') && e('200');
r($commonTest->apiPostTest('https://api.example.com/success', array('data' => 'test'))) && p('code,message') && e('200,Success');
r($commonTest->apiPostTest('https://api.example.com/error', array('action' => 'fail'))) && p('code,message') && e('400,Bad Request');
r($commonTest->apiPostTest('', array('empty' => 'url'))) && p() && e('Empty URL');
r($commonTest->apiPostTest('invalid-url', array('test' => 'data'))) && p('code') && e('600');