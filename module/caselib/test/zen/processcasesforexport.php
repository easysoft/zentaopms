#!/usr/bin/env php
<?php

/**

title=测试 caselibZen::processCasesForExport();
timeout=0
cid=0

- 执行caselibTest模块的processCasesForExportTest方法，参数是$normalCases, 1, array  @1
- 执行caselibTest模块的processCasesForExportTest方法，参数是array  @1
- 执行caselibTest模块的processCasesForExportTest方法，参数是$casesWithSteps, 1, array  @1
- 执行caselibTest模块的processCasesForExportTest方法，参数是$normalCases, 1, array  @1
- 执行caselibTest模块的processCasesForExportTest方法，参数是$casesWithLinkCase, 1, array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/caselib.unittest.class.php';

zenData('user')->gen(10);
zenData('module')->gen(10);
zenData('case')->gen(10);
zenData('casestep')->gen(20);
zenData('file')->gen(5);

su('admin');

$caselibTest = new caselibTest();

// 步骤1：测试正常用例数据的导出处理
$normalCases = array(
    1 => (object) array(
        'id' => 1,
        'title' => 'Test Case 1',
        'module' => 1,
        'pri' => 1,
        'type' => 'feature',
        'status' => 'normal',
        'openedBy' => 'admin',
        'lastEditedBy' => 'user1',
        'openedDate' => '2023-01-01 10:00:00',
        'lastRunDate' => '2023-01-02 10:00:00',
        'stage' => 'unittest,feature',
        'linkCase' => '',
        'stepDesc' => '',
        'stepExpect' => ''
    )
);
r($caselibTest->processCasesForExportTest($normalCases, 1, array('fileType' => 'csv'), 'count')) && p() && e('1');

// 步骤2：测试空用例数组的处理
r($caselibTest->processCasesForExportTest(array(), 1, array('fileType' => 'csv'), 'is_empty')) && p() && e('1');

// 步骤3：测试包含步骤的用例导出处理，验证返回的用例数量
$casesWithSteps = array(
    2 => (object) array(
        'id' => 2,
        'title' => 'Test Case with Steps',
        'module' => 2,
        'pri' => 2,
        'type' => 'performance',
        'status' => 'normal',
        'openedBy' => 'admin',
        'lastEditedBy' => 'user2',
        'openedDate' => '2023-01-01 10:00:00',
        'lastRunDate' => '2023-01-02 10:00:00',
        'stage' => 'feature',
        'linkCase' => '',
        'stepDesc' => '',
        'stepExpect' => ''
    )
);
r($caselibTest->processCasesForExportTest($casesWithSteps, 1, array('fileType' => 'csv'), 'count')) && p() && e('1');

// 步骤4：测试HTML文件类型的导出处理
r($caselibTest->processCasesForExportTest($normalCases, 1, array('fileType' => 'html'), 'first_case_id')) && p() && e('1');

// 步骤5：测试包含关联用例的导出处理
$casesWithLinkCase = array(
    3 => (object) array(
        'id' => 3,
        'title' => 'Test Case with Link',
        'module' => 3,
        'pri' => 3,
        'type' => 'interface',
        'status' => 'normal',
        'openedBy' => 'admin',
        'lastEditedBy' => 'user3',
        'openedDate' => '2023-01-01 10:00:00',
        'lastRunDate' => '2023-01-02 10:00:00',
        'stage' => 'feature,integration',
        'linkCase' => '1,2',
        'stepDesc' => '',
        'stepExpect' => ''
    )
);
r($caselibTest->processCasesForExportTest($casesWithLinkCase, 1, array('fileType' => 'csv'), 'has_linkCase')) && p() && e('1');