#!/usr/bin/env php
<?php
/**

title=测试 caselibZen::processCasesForExport();
timeout=0
cid=1

- 测试空数据 @0
- 测试有数据
 - 第1条的id属性 @1
 - 第1条的title属性 @这个是测试用例1
 - 第1条的pri属性 @1
 - 第1条的type属性 @功能测试
 - 第1条的stage属性 @单元测试环节
- 测试不存在的数据 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('case')->loadYaml('testcase_processcasesforexport')->gen(10);
zenData('casestep')->loadYaml('casestep_processstepforexport')->gen(20);
zenData('module')->loadYaml('module_processcasesforexport')->gen(10);
zenData('testsuite')->gen(5);
su('admin');

$caseIdList[] = array();
$caseIdList[] = range(1, 10);
$caseIdList[] = range(11, 20);

$caselibTest = new caselibZenTest();
r($caselibTest->processCasesForExportTest($caseIdList[0], 0)) && p()                            && e('0');                                         // 测试空数据
r($caselibTest->processCasesForExportTest($caseIdList[1], 1)) && p('1:id,title,pri,type,stage') && e('1,这个是测试用例1,1,功能测试,单元测试环节'); // 测试有数据
r($caselibTest->processCasesForExportTest($caseIdList[2], 1)) && p()                            && e('0');                                         // 测试不存在的数据
