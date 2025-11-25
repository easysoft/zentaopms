#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::prepareReviewData();
timeout=0
cid=0

- 执行testcaseTest模块的prepareReviewDataTest方法，参数是1, $oldCase1, $postData1
 - 属性status @normal
 - 属性result @pass
- 执行testcaseTest模块的prepareReviewDataTest方法，参数是2, $oldCase2, $postData2
 - 属性status @normal
 - 属性result @fail
- 执行testcaseTest模块的prepareReviewDataTest方法，参数是3, $oldCase3, $postData3  @0
- 执行testcaseTest模块的prepareReviewDataTest方法，参数是4, $oldCase4, $postData4
 - 属性status @normal
 - 属性reviewedBy @admin,user1,user2
- 执行testcaseTest模块的prepareReviewDataTest方法，参数是5, $oldCase5, $postData5
 - 属性status @normal
 - 属性reviewedBy @admin

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

su('admin');

$testcaseTest = new testcaseZenTest();

// 创建测试用例对象
$oldCase1 = new stdClass();
$oldCase1->id = 1;
$oldCase1->status = 'wait';

$oldCase2 = new stdClass();
$oldCase2->id = 2;
$oldCase2->status = 'normal';

$oldCase3 = new stdClass();
$oldCase3->id = 3;
$oldCase3->status = 'wait';

$oldCase4 = new stdClass();
$oldCase4->id = 4;
$oldCase4->status = 'normal';

$oldCase5 = new stdClass();
$oldCase5->id = 5;
$oldCase5->status = 'wait';

// 测试数据1: 评审通过
$postData1 = array(
    'result' => 'pass',
    'reviewedBy' => array('admin'),
    'reviewedDate' => '2024-01-15',
    'comment' => '评审通过',
    'uid' => ''
);

// 测试数据2: 评审不通过
$postData2 = array(
    'result' => 'fail',
    'reviewedBy' => array('admin'),
    'reviewedDate' => '2024-01-15',
    'comment' => '需要修改',
    'uid' => ''
);

// 测试数据3: 未选择评审结果
$postData3 = array(
    'result' => '',
    'reviewedBy' => array('admin'),
    'reviewedDate' => '2024-01-15',
    'uid' => ''
);

// 测试数据4: 多个评审人员
$postData4 = array(
    'result' => 'pass',
    'reviewedBy' => array('admin', 'user1', 'user2'),
    'reviewedDate' => '2024-01-15',
    'comment' => '多人评审通过',
    'uid' => ''
);

// 测试数据5: 单个评审人员
$postData5 = array(
    'result' => 'pass',
    'reviewedBy' => array('admin'),
    'reviewedDate' => '2024-01-15',
    'comment' => '单人评审通过',
    'uid' => ''
);

r($testcaseTest->prepareReviewDataTest(1, $oldCase1, $postData1)) && p('status,result') && e('normal,pass');
r($testcaseTest->prepareReviewDataTest(2, $oldCase2, $postData2)) && p('status,result') && e('normal,fail');
r($testcaseTest->prepareReviewDataTest(3, $oldCase3, $postData3)) && p() && e('0');
r($testcaseTest->prepareReviewDataTest(4, $oldCase4, $postData4)) && p('status,reviewedBy') && e('normal,admin,user1,user2');
r($testcaseTest->prepareReviewDataTest(5, $oldCase5, $postData5)) && p('status,reviewedBy') && e('normal,admin');