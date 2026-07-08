#!/usr/bin/env php
<?php

/**

title=测试 repoZen::getSearchFormQuery();
timeout=0
cid=0

- 测试有日期范围查询（>= 操作符）@2023-01-01
- 测试有日期范围查询（<= 操作符）@2023-12-31
- 测试有提交者搜索条件 @admin
- 测试有提交信息搜索条件 @fix bug
- 测试有提交信息搜索条件 @feat

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

su('admin');

$repoTest = new repoZenTest();

r($repoTest->getSearchFormQueryDateBeginTest())  && p('begin')     && e('2023-01-01'); // 测试日期开始范围
r($repoTest->getSearchFormQueryDateEndTest())    && p('end')       && e('2023-12-31'); // 测试日期结束范围
r($repoTest->getSearchFormQueryCommitterTest())  && p('committer') && e('admin'); // 测试提交者搜索
r($repoTest->getSearchFormQueryCommitTest())     && p('commit')    && e('fix bug'); // 测试提交信息搜索
r($repoTest->getSearchFormQueryCommitFeatTest()) && p('commit')    && e('feat'); // 测试提交信息搜索
