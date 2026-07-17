#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 repoZen->buildTaskSearchForm();
timeout=0
cid=0

- 正常参数 >> 返回1
- 空modules >> 返回1
- 空executionPairs >> 返回1
- 不同browseType >> 返回1
- queryID=0 >> 返回1

*/

su('admin');

$zenTest = new repoZenTest();

r($zenTest->buildTaskSearchFormTest(1, 'HEAD', 'all', 0, array(), array())) && p() && e('1');    // 正常参数
r($zenTest->buildTaskSearchFormTest(1, 'HEAD', 'all', 0, array(1), array())) && p() && e('1');   // 空executionPairs
r($zenTest->buildTaskSearchFormTest(1, 'HEAD', 'all', 0, array(), array(1 => 'exec1'))) && p() && e('1'); // 空modules
r($zenTest->buildTaskSearchFormTest(1, 'HEAD', 'unresolved', 0, array(), array())) && p() && e('1'); // 不同browseType
r($zenTest->buildTaskSearchFormTest(1, 'HEAD', 'all', 0, array(), array())) && p() && e('1');    // 第二次调用
