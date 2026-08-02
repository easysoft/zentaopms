#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 repoZen->checkrepointernet();
timeout=0
cid=0

- 调用checkRepoInternetTest验证返回 @1
- 第二次调用返回一致 @1
- 第三次调用返回一致 @1
- 第四次调用返回一致 @1
- 第五次调用返回一致 @1

*/

su('admin');
$test = new repoZenTest();

r($test->checkRepoInternetAvailableTest()) && p() && e('1');
r($test->checkRepoInternetAvailableTest()) && p() && e('1');
r($test->checkRepoInternetAvailableTest()) && p() && e('1');
r($test->checkRepoInternetAvailableTest()) && p() && e('1');
r($test->checkRepoInternetAvailableTest()) && p() && e('1');
