#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::responseAfterShowImport();
timeout=0
cid=0

- 执行testcaseZenTest模块的responseAfterShowImportTest方法，参数是1, '0', 0, '', '', $mockData1
 - 属性result @fail
 - 属性message @Database error occurred
- 执行testcaseZenTest模块的responseAfterShowImportTest方法，参数是1, '0', 100, '/tmp/test.csv', '', $mockData2
 - 属性result @success
 - 属性load @/testcase-browse-productID=1.html
- 执行testcaseZenTest模块的responseAfterShowImportTest方法，参数是2, '1', 100, '', '', $mockData3
- 执行testcaseZenTest模块的responseAfterShowImportTest方法，参数是3, '0', 200, '', '', $mockData4
 - 属性result @success
 - 属性load @/testcase-showImport-productID=3&branch=0&pagerID=2&maxImport=200&insert=.html
- 执行testcaseZenTest模块的responseAfterShowImportTest方法，参数是4, '2', 50, '', 'Import completed', $mockData5
 - 属性result @success
 - 属性message @/Import completed
- 执行testcaseZenTest模块的responseAfterShowImportTest方法，参数是5, '', 100, '', '', $mockData6
 - 属性result @success
 - 属性load @/testcase-browse-productID=5.html

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

su('admin');

$testcaseTest = new testcaseTest();

// 测试步骤1:测试存在 DAO 错误时的响应
$mockData1 = array('daoError' => array('Database error occurred'));
r($testcaseTest->responseAfterBatchCreateTest(1, 0, $mockData1)) && p('result,message') && e('fail,Database error occurred');

// 测试步骤2:测试 Ajax 模态框请求时的响应
$mockData2 = array(
    'request' => array('HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'),
    'app' => array('rawParams' => array('modal'))
);
r($testcaseTest->responseAfterBatchCreateTest(1, 0, $mockData2)) && p('result,closeModal,load') && e('success,1,1');

// 测试步骤3:测试 JSON 视图类型时的响应
$mockData3 = array('viewType' => 'json');
r($testcaseTest->responseAfterBatchCreateTest(1, 0, $mockData3)) && p('result') && e('success');

// 测试步骤4:测试在 QA tab 下的默认响应
$mockData4 = array('app' => array('tab' => 'qa'));
r($testcaseTest->responseAfterBatchCreateTest(1, 0, $mockData4)) && p('result,load') && e('success,/testcase-browse-productID=1&branch=0.html');

// 测试步骤5:测试在 project tab 下的默认响应
$mockData5 = array(
    'app' => array('tab' => 'project'),
    'session' => array('project' => 10)
);
r($testcaseTest->responseAfterBatchCreateTest(2, 1, $mockData5)) && p('result,load') && e('success,/project-testcase-projectID=10&productID=2&branch=1.html');

// 测试步骤6:测试在 execution tab 下的默认响应
$mockData6 = array(
    'app' => array('tab' => 'execution'),
    'session' => array('execution' => 20)
);
r($testcaseTest->responseAfterBatchCreateTest(3, 2, $mockData6)) && p('result,load') && e('success,/execution-testcase-executionID=20&productID=3&branch=2.html');