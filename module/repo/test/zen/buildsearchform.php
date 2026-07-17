#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 repoZen->buildSearchForm();
timeout=0
cid=0

- queryID=0 >> 返回1
- queryID=1 >> 返回1
- 空actionURL >> 返回1
- 有效actionURL >> 返回1
- queryID=-1 >> 返回1

*/

su('admin');

$zenTest = new repoZenTest();

r($zenTest->buildSearchFormTest(0, '/repo-browse')) && p() && e('1');   // queryID=0
r($zenTest->buildSearchFormTest(1, '/repo-browse')) && p() && e('1');   // queryID=1
r($zenTest->buildSearchFormTest(0, '')) && p() && e('1');               // 空actionURL
r($zenTest->buildSearchFormTest(0, '/repo-log')) && p() && e('1');      // 有效actionURL
r($zenTest->buildSearchFormTest(-1, '/repo-browse')) && p() && e('1'); // queryID=-1
