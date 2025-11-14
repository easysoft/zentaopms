#!/usr/bin/env php
<?php
/**

title=测试 testcaseZen::buildDataForImportFromLib();
timeout=0
cid=19080

- 测试用例属性是否存在 @1
- 测试用例步骤是否存在 @1
- 测试用例文件是否存在 @1
- 测试已导入的用例是否存在 @1
- 获取用例ID @1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';
zenData('case')->loadYaml('case')->gen(20);
zenData('product')->gen(5);
zenData('user')->gen(5);
su('admin');

$postData = array('branch' => array(), 'caseIdList' => range(1, 20));

$testcaseTester = new testcaseZenTest();
$result = $testcaseTester->buildDataForImportFromLibTest(1, 'all', 0, $postData);
r(isset($result[0])) && p() && e('1');                                                   // 测试用例属性是否存在
r(isset($result[1])) && p() && e('1');                                                   // 测试用例步骤是否存在
r(isset($result[2])) && p() && e('1');                                                   // 测试用例文件是否存在
r(isset($result[3])) && p() && e('1');                                                   // 测试已导入的用例是否存在
r($result[3]) && p('', ';') && e('1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,'); // 获取用例ID
