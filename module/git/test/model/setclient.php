#!/usr/bin/env php
<?php

/**

title=测试 gitModel::setClient();
timeout=0
cid=16553

- 步骤1：正常设置git客户端 @git
- 步骤2：正常设置http客户端 @http://https-test
- 步骤3：设置空字符串客户端 @0
- 步骤4：设置null值客户端 @0
- 步骤5：传入null参数 @null_repo

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$gitTest = new gitModelTest();

$repo = new stdclass();
$repo->client = 'git';
r($gitTest->setClientTest($repo)) && p() && e('git'); // 步骤1：正常设置git客户端

$repo->client = 'http://https-test';
r($gitTest->setClientTest($repo)) && p() && e('http://https-test'); // 步骤2：正常设置http客户端

$repo->client = '';
r($gitTest->setClientTest($repo)) && p() && e('0'); // 步骤3：设置空字符串客户端

$repo->client = null;
r($gitTest->setClientTest($repo)) && p() && e('0'); // 步骤4：设置null值客户端

r($gitTest->setClientTest(null)) && p() && e('null_repo'); // 步骤5：传入null参数