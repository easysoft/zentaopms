#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 repoZen->buildSystemSearchForm();
timeout=0
cid=0

- 默认参数 >> 返回1
- 带queryID >> 返回1
- cacheSearchFunc=false >> 返回1
- cacheSearchFunc=true >> 返回1
- 空actionURL >> 返回1

*/

su('admin');

$zenTest = new repoZenTest();

r($zenTest->buildSystemSearchFormTest(0, '/repo-system')) && p() && e('1');           // 默认参数
r($zenTest->buildSystemSearchFormTest(1, '/repo-system')) && p() && e('1');           // 带queryID
r($zenTest->buildSystemSearchFormTest(0, '/repo-system', false)) && p() && e('1');    // cacheSearchFunc=false
r($zenTest->buildSystemSearchFormTest(0, '/repo-system', true)) && p() && e('1');     // cacheSearchFunc=true
r($zenTest->buildSystemSearchFormTest(0, '')) && p() && e('1');                       // 空actionURL
