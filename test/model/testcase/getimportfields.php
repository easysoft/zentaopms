#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testcase.class.php';
su('admin');

/**

title=测试 testcaseModel->getImportFields();
cid=1
pid=1

测试获取product 1 可以导出的字段的名称 >> 用例编号,所属产品,分支/平台,所属模块,B,R,S
测试获取product 2 可以导出的字段的名称 >> 用例编号,所属产品,分支/平台,所属模块,B,R,S
测试获取product 3 可以导出的字段的名称 >> 用例编号,所属产品,分支/平台,所属模块,B,R,S
测试获取product 4 可以导出的字段的名称 >> 用例编号,所属产品,分支/平台,所属模块,B,R,S
测试获取product 5 可以导出的字段的名称 >> 用例编号,所属产品,分支/平台,所属模块,B,R,S

*/

$productIDList = array(1, 2, 3, 4, 5);

$testcase = new testcaseTest();

r($testcase->getImportFieldsTest($productIDList[0])) && p('id,product,branch,module,bugsAB,resultsAB,stepNumberAB') && e('用例编号,所属产品,分支/平台,所属模块,B,R,S'); // 测试获取product 1 可以导出的字段的名称
r($testcase->getImportFieldsTest($productIDList[1])) && p('id,product,branch,module,bugsAB,resultsAB,stepNumberAB') && e('用例编号,所属产品,分支/平台,所属模块,B,R,S'); // 测试获取product 2 可以导出的字段的名称
r($testcase->getImportFieldsTest($productIDList[2])) && p('id,product,branch,module,bugsAB,resultsAB,stepNumberAB') && e('用例编号,所属产品,分支/平台,所属模块,B,R,S'); // 测试获取product 3 可以导出的字段的名称
r($testcase->getImportFieldsTest($productIDList[3])) && p('id,product,branch,module,bugsAB,resultsAB,stepNumberAB') && e('用例编号,所属产品,分支/平台,所属模块,B,R,S'); // 测试获取product 4 可以导出的字段的名称
r($testcase->getImportFieldsTest($productIDList[4])) && p('id,product,branch,module,bugsAB,resultsAB,stepNumberAB') && e('用例编号,所属产品,分支/平台,所属模块,B,R,S'); // 测试获取product 5 可以导出的字段的名称