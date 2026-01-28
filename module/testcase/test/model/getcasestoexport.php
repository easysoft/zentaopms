#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

function initData()
{
    zenData('case')->gen(10);

    $testRun = zenData('testrun');
    $testRun->task->range('1-5{2}');
    $testRun->gen(10);
}

initData();

/**

title=测试 testcaseModel->getCasesToExport();
timeout=0
cid=18987

- 测试获取产品 1 的用例
 - 第1条的title属性 @这个是测试用例1
 - 第2条的title属性 @这个是测试用例2
 - 第3条的title属性 @这个是测试用例3
 - 第4条的title属性 @这个是测试用例4
- 测试获取产品 1 测试单为 1 的用例
 - 第1条的title属性 @这个是测试用例1
 - 第2条的title属性 @这个是测试用例2
- 测试获取选中的用例
 - 第1条的title属性 @这个是测试用例1
 - 第2条的title属性 @这个是测试用例2
 - 第3条的title属性 @这个是测试用例3

*/

$_COOKIE['checkedItem'] = '1,2,3';

$testcaseOnlyConditionList  = array(true, false);
$testcaseQueryConditionList = array('product = 1', 'select * from ' . TABLE_CASE . ' where product = 1');

$exportTypeList = array('all', 'selected');
$taskIDList     = array('0', '1');

$testcase = new testcaseModelTest();
r($testcase->getCasesToExportTest($testcaseOnlyConditionList[0], $testcaseQueryConditionList[0], $exportTypeList[0], $taskIDList[0])) && p('1:title;2:title;3:title;4:title') && e('这个是测试用例1;这个是测试用例2;这个是测试用例3;这个是测试用例4'); // 测试获取产品 1 的用例
r($testcase->getCasesToExportTest($testcaseOnlyConditionList[0], $testcaseQueryConditionList[0], $exportTypeList[0], $taskIDList[1])) && p('1:title;2:title')                 && e('这个是测试用例1;这个是测试用例2');                                 // 测试获取产品 1 测试单为 1 的用例
r($testcase->getCasesToExportTest($testcaseOnlyConditionList[1], $testcaseQueryConditionList[1], $exportTypeList[1], $taskIDList[0])) && p('1:title;2:title;3:title')         && e('这个是测试用例1;这个是测试用例2;这个是测试用例3');                 // 测试获取选中的用例