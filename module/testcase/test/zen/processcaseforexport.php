#!/usr/bin/env php
<?php
/**

title=测试 testcaseZen::processCaseForExport();
timeout=0
cid=0

- 测试处理ID为1的导出数据
 - 属性id @1
 - 属性title @这个是测试用例1
 - 属性status @待评审
 - 属性linkCase @0
 - 属性fromCaseID @0
- 测试处理ID为2的导出数据
 - 属性id @2
 - 属性title @这个是测试用例2
 - 属性status @正常
 - 属性linkCase @0
 - 属性fromCaseID @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';
zenData('case')->gen(10);
zenData('product')->gen(1);
su('admin');

$caseIdList = range(1, 10);
$testcaseTest = new testcaseZenTest();
r($testcaseTest->processCaseForExportTest($caseIdList[0])) && p('id,title,status,linkCase,fromCaseID') && e('1,这个是测试用例1,待评审,0,0'); // 测试处理ID为1的导出数据
r($testcaseTest->processCaseForExportTest($caseIdList[1])) && p('id,title,status,linkCase,fromCaseID') && e('2,这个是测试用例2,正常,0,0');   // 测试处理ID为2的导出数据
