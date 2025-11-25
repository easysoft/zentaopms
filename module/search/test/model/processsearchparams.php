#!/usr/bin/env php
<?php

/**

title=测试 searchModel::processSearchParams();
timeout=0
cid=18306

- 测试正常情况：session中有完整的搜索函数配置 @array
- 测试缺少funcModel的情况 @array
- 测试缺少funcName的情况 @array
- 测试缺少funcArgs的情况 @array
- 测试cacheSearchFunc参数为true的情况 @array

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

su('admin');

$search = new searchTest();

r($search->processSearchParamsTest('story', false)) && p() && e('array'); // 测试正常情况：session中有完整的搜索函数配置
r($search->processSearchParamsTest('bug', false)) && p() && e('array'); // 测试缺少funcModel的情况
r($search->processSearchParamsTest('task', false)) && p() && e('array'); // 测试缺少funcName的情况
r($search->processSearchParamsTest('testcase', false)) && p() && e('array'); // 测试缺少funcArgs的情况
r($search->processSearchParamsTest('story', true)) && p() && e('array'); // 测试cacheSearchFunc参数为true的情况