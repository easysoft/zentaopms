#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 repoZen->getSearchForm();
timeout=0
cid=0

- queryID=0 getSql=false >> 返回1
- queryID=0 getSql=true >> 返回1
- queryID=1 getSql=false >> 返回1
- queryID=1 getSql=true >> 返回1
- queryID=-1 >> 返回1

*/

su('admin');

$zenTest = new repoZenTest();

r($zenTest->getSearchFormTest(0, false)) && p() && e('1');   // queryID=0 getSql=false
r($zenTest->getSearchFormTest(0, true)) && p() && e('1');    // queryID=0 getSql=true
r($zenTest->getSearchFormTest(1, false)) && p() && e('1');   // queryID=1 getSql=false
r($zenTest->getSearchFormTest(1, true)) && p() && e('1');    // queryID=1 getSql=true
r($zenTest->getSearchFormTest(-1, false)) && p() && e('1'); // queryID=-1
