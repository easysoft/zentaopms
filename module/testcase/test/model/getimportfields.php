#!/usr/bin/env php
<?php
/**

title=测试 testcaseModel->getImportFields();
cid=1
pid=1

- 测试获取product 1 可以导出的字段的名称
 - 属性id @用例编号
 - 属性product @所属产品
 - 属性branch @平台/分支
 - 属性module @所属模块
 - 属性bugsAB @B
 - 属性resultsAB @R
 - 属性stepNumberAB @S
- 测试获取product 2 可以导出的字段的名称
 - 属性id @用例编号
 - 属性product @所属产品
 - 属性branch @平台/分支
 - 属性module @所属模块
 - 属性bugsAB @B
 - 属性resultsAB @R
 - 属性stepNumberAB @S
- 测试获取product 3 可以导出的字段的名称
 - 属性id @用例编号
 - 属性product @所属产品
 - 属性branch @平台/分支
 - 属性module @所属模块
 - 属性bugsAB @B
 - 属性resultsAB @R
 - 属性stepNumberAB @S
- 测试获取product 41 可以导出的字段的名称
 - 属性id @用例编号
 - 属性product @所属产品
 - 属性branch @分支
 - 属性module @所属模块
 - 属性bugsAB @B
 - 属性resultsAB @R
 - 属性stepNumberAB @S
- 测试获取product 81 可以导出的字段的名称
 - 属性id @用例编号
 - 属性product @所属产品
 - 属性branch @平台
 - 属性module @所属模块
 - 属性bugsAB @B
 - 属性resultsAB @R
 - 属性stepNumberAB @S
- 测试获取product 0 可以导出的字段的名称
 - 属性id @用例编号
 - 属性product @所属产品
 - 属性branch @平台
 - 属性module @所属模块
 - 属性bugsAB @B
 - 属性resultsAB @R
 - 属性stepNumberAB @S

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';

zdTable('product')->gen('90');

global $tester, $config;
$tester->loadModel('testcase');
$config->testcase->exportFields .= ',bugsAB,resultsAB,stepNumberAB';

$productIDList = array(1, 2, 3, 41, 81);

$testcase = new testcaseTest();

r($testcase->getImportFieldsTest($productIDList[0])) && p('id,product,branch,module,bugsAB,resultsAB,stepNumberAB') && e('用例编号,所属产品,平台/分支,所属模块,B,R,S'); // 测试获取product 1 可以导出的字段的名称
r($testcase->getImportFieldsTest($productIDList[1])) && p('id,product,branch,module,bugsAB,resultsAB,stepNumberAB') && e('用例编号,所属产品,平台/分支,所属模块,B,R,S'); // 测试获取product 2 可以导出的字段的名称
r($testcase->getImportFieldsTest($productIDList[2])) && p('id,product,branch,module,bugsAB,resultsAB,stepNumberAB') && e('用例编号,所属产品,平台/分支,所属模块,B,R,S'); // 测试获取product 3 可以导出的字段的名称
r($testcase->getImportFieldsTest($productIDList[3])) && p('id,product,branch,module,bugsAB,resultsAB,stepNumberAB') && e('用例编号,所属产品,分支,所属模块,B,R,S');      // 测试获取product 41 可以导出的字段的名称
r($testcase->getImportFieldsTest($productIDList[4])) && p('id,product,branch,module,bugsAB,resultsAB,stepNumberAB') && e('用例编号,所属产品,平台,所属模块,B,R,S');      // 测试获取product 81 可以导出的字段的名称
r($testcase->getImportFieldsTest())                  && p('id,product,branch,module,bugsAB,resultsAB,stepNumberAB') && e('用例编号,所属产品,平台,所属模块,B,R,S');      // 测试获取product 0 可以导出的字段的名称
