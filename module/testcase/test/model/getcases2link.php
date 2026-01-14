#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('case')->gen('100');

/**

title=测试 testcaseModel->getCases2Link();
timeout=0
cid=18986

- 获取相关的用例的数量 @3
- 获取相关的用例
 - 第2条的id属性 @2
 - 第2条的title属性 @这个是测试用例2
 - 第2条的product属性 @1
 - 第2条的story属性 @2

*/

$caseIDList     = array('1');
$browseTypeList = array('bySearch');

$testcase = new testcaseModelTest();

$result = $testcase->getCases2LinkTest($caseIDList[0], $browseTypeList[0]);
r(count($result)) && p('') && e('3'); // 获取相关的用例的数量
r($result)        && p('2:id,title,product,story') && e('2,这个是测试用例2,1,2'); // 获取相关的用例