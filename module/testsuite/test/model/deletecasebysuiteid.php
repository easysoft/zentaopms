#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('case')->gen(20);
zenData('suitecase')->gen(3);
zenData('testsuite')->gen(2);
zenData('user')->gen(1);

su('admin');

/**

title=测试 testsuiteModel->deleteCaseBySuiteIDTest();
timeout=0
cid=19139

- 检查当前关联的用例数量 @2
- 测试suiteID值为1 @2
- 检查当前关联的用例数量 @0
- 检查当前关联的用例数量 @1
- 测试suiteID值为2 @1
- 检查当前关联的用例数量 @0

*/
$suiteID = 1;
$cases   = array(1, 2);

$testsuite = new testsuiteModelTest();

r(count($testsuite->getLinkedCasesTest($suiteID)))       && p() && e('2'); //检查当前关联的用例数量
r($testsuite->deleteCaseBySuiteIDTest($cases, $suiteID)) && p() && e('2'); //测试suiteID值为1
r(count($testsuite->getLinkedCasesTest($suiteID)))       && p() && e('0'); //检查当前关联的用例数量

$suiteID = 2;
$cases   = array(3);
r(count($testsuite->getLinkedCasesTest($suiteID)))       && p() && e('1'); //检查当前关联的用例数量
r($testsuite->deleteCaseBySuiteIDTest($cases, $suiteID)) && p() && e('1'); //测试suiteID值为2
r(count($testsuite->getLinkedCasesTest($suiteID)))       && p() && e('0'); //检查当前关联的用例数量