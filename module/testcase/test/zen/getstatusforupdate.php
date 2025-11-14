#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::getStatusForUpdate();
timeout=0
cid=19098

- 执行$result1[0] @0
- 执行$result1[1] @normal
- 执行$result2[0] @1
- 执行$result2[1] @wait
- 执行$result5['message'][0] @该记录可能已经被改动。请刷新页面重新编辑！

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备
$caseTable = zenData('case');
$caseTable->loadYaml('case_getstatusforupdate', false, 2)->gen(5);

$stepTable = zenData('casestep');
$stepTable->loadYaml('casestep_getstatusforupdate', false, 2)->gen(10);

// 3. 设置配置以启用审核
global $config;
$config->testcase->needReview = '1';

// 4. 用户登录
su('admin');

// 5. 创建测试实例
$testcaseTest = new testcaseZenTest();

// 6. 准备测试数据 - 创建用例对象，包含步骤信息
$case = new stdClass();
$case->id = 1;
$case->title = '测试用例标题1';
$case->precondition = '前置条件描述1';
$case->status = 'normal';
$case->lastEditedDate = '2023-09-01 10:00:00';

// 创建步骤对象
$step1 = new stdClass();
$step1->desc = '步骤描述1';
$step1->expect = '期望结果1';
$step1->type = 'step';

$step2 = new stdClass();
$step2->desc = '步骤描述2';
$step2->expect = '期望结果2';
$step2->type = 'step';

$case->steps = array($step1, $step2);

// 7. 测试步骤
// 步骤1：正常情况下无变更 - 相同的数据
$postData1 = array(
    'title' => '测试用例标题1',
    'precondition' => '前置条件描述1',
    'status' => 'normal',
    'lastEditedDate' => '2023-09-01 10:00:00',
    'steps' => array('步骤描述1', '步骤描述2'),
    'stepType' => array('step', 'step'),
    'expects' => array('期望结果1', '期望结果2')
);
$result1 = $testcaseTest->getStatusForUpdateTest($case, $postData1);
r($result1[0]) && p() && e('0');
r($result1[1]) && p() && e('normal');

// 步骤2：步骤内容发生变更
$postData2 = array(
    'title' => '测试用例标题1',
    'precondition' => '前置条件描述1',
    'status' => 'normal',
    'lastEditedDate' => '2023-09-01 10:00:00',
    'steps' => array('修改后的步骤描述', '步骤描述2'),
    'stepType' => array('step', 'step'),
    'expects' => array('期望结果1', '期望结果2')
);
$result2 = $testcaseTest->getStatusForUpdateTest($case, $postData2);
r($result2[0]) && p() && e('1');
r($result2[1]) && p() && e('wait');

// 步骤3：并发编辑冲突检测 - 不同的lastEditedDate
$postData5 = array(
    'title' => '测试用例标题1',
    'precondition' => '前置条件描述1',
    'status' => 'normal',
    'lastEditedDate' => '2023-09-01 11:00:00',  // 不同的时间
    'steps' => array('步骤描述1', '步骤描述2'),
    'stepType' => array('step', 'step'),
    'expects' => array('期望结果1', '期望结果2')
);
$result5 = $testcaseTest->getStatusForUpdateTest($case, $postData5);
r($result5['message'][0]) && p() && e('该记录可能已经被改动。请刷新页面重新编辑！');