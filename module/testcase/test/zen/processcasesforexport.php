#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::processCasesForExport();
timeout=0
cid=0

- 测试处理导出数据
 - 第0条的id属性 @1
 - 第0条的title属性 @这个是测试用例1
 - 第0条的status属性 @待评审
 - 第0条的linkCase属性 @0
 - 第0条的fromCaseID属性 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('case')->gen(10);
zenData('product')->gen(1);
zenData('user')->gen(5);
su('admin');

$caseIdList = range(1, 10);
$testcaseTest = new testcaseZenTest();
r($testcaseTest->processCasesForExportTest($caseIdList, 1, 0)) && p('0:id,title,status,linkCase,fromCaseID') && e('1,这个是测试用例1,待评审,0,0'); // 测试处理导出数据
