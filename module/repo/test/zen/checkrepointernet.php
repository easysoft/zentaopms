#!/usr/bin/env php
<?php

/**

title=测试 repoZen::checkRepoInternet();
timeout=0
cid=18132

- 步骤1：空repo对象 @0
- 步骤2：GitHub地址可访问 @0
- 步骤3：localhost地址不可访问 @1
- 步骤4：无效地址不可访问 @1
- 步骤5：没有http字段 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

su('admin');

$repoTest = new repoZenTest();

r($repoTest->checkRepoInternetTest(null)) && p() && e('0'); // 步骤1：空repo对象
r($repoTest->checkRepoInternetTest((object)array('path' => 'https://github.com/test/repo'))) && p() && e('0'); // 步骤2：GitHub地址可访问
r($repoTest->checkRepoInternetTest((object)array('client' => 'http://localhost:3000/repo'))) && p() && e('1'); // 步骤3：localhost地址不可访问
r($repoTest->checkRepoInternetTest((object)array('apiPath' => 'http://invalid-url.test/api'))) && p() && e('1'); // 步骤4：无效地址不可访问
r($repoTest->checkRepoInternetTest((object)array('name' => 'test', 'SCM' => 'Git'))) && p() && e('0'); // 步骤5：没有http字段