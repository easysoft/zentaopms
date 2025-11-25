#!/usr/bin/env php
<?php

/**

title=测试 caselibZen::processStepForExport();
timeout=0
cid=15558

- 执行caselibTest模块的processStepForExportTest方法，参数是$case1, $emptySteps, array  @0
- 执行caselibTest模块的processStepForExportTest方法，参数是$case2, $singleSteps, array  @1
- 执行caselibTest模块的processStepForExportTest方法，参数是$case3, $multiLevelSteps, array  @3
- 执行caselibTest模块的processStepForExportTest方法，参数是$case4, $specialSteps, array  @1
- 执行caselibTest模块的processStepForExportTest方法，参数是$case5, $htmlSteps, array  @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/caselib.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$caseTable = zenData('case');
$caseTable->id->range('1-5');
$caseTable->title->range('测试用例1,测试用例2,测试用例3,测试用例4,测试用例5');
$caseTable->gen(5);

$stepTable = zenData('casestep');
$stepTable->id->range('1-10');
$stepTable->parent->range('0,0,1,1,0,5,5,0,0,0');
$stepTable->case->range('1{2},2{2},3{3},4{2},5{1}');
$stepTable->version->range('1{10}');
$stepTable->type->range('step,item,group,step,step{6}');
$stepTable->desc->range('步骤1,步骤1.1,步骤组,步骤1.1.1,步骤2,步骤2.1,步骤2.2,包含"引号"的步骤,另一个步骤,简单步骤');
$stepTable->expect->range('预期1,预期1.1,预期组,预期1.1.1,预期2,预期2.1,预期2.2,包含"引号"的预期,另一个预期,简单预期');
$stepTable->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$caselibTest = new caselibTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 测试步骤1：无相关步骤的用例导出
$case1 = new stdclass();
$case1->id = 999;
$case1->stepDesc = '';
$case1->stepExpect = '';
$emptySteps = array();
r($caselibTest->processStepForExportTest($case1, $emptySteps, array('fileType' => 'csv'), 'has_stepDesc')) && p() && e('0');

// 测试步骤2：单个步骤的用例导出处理
$case2 = new stdclass();
$case2->id = 1;
$case2->stepDesc = '';
$case2->stepExpect = '';
$singleSteps = array(
    1 => array(
        (object)array('id' => 1, 'parent' => 0, 'desc' => '步骤1', 'expect' => '预期1')
    )
);
r($caselibTest->processStepForExportTest($case2, $singleSteps, array('fileType' => 'csv'), 'first_step_number')) && p() && e('1');

// 测试步骤3：多层级步骤的用例导出处理
$case3 = new stdclass();
$case3->id = 2;
$case3->stepDesc = '';
$case3->stepExpect = '';
$multiLevelSteps = array(
    2 => array(
        (object)array('id' => 2, 'parent' => 0, 'desc' => '步骤1', 'expect' => '预期1'),
        (object)array('id' => 3, 'parent' => 2, 'desc' => '步骤1.1', 'expect' => '预期1.1'),
        (object)array('id' => 4, 'parent' => 3, 'desc' => '步骤1.1.1', 'expect' => '预期1.1.1')
    )
);
r($caselibTest->processStepForExportTest($case3, $multiLevelSteps, array('fileType' => 'csv'), 'stepDesc_lines')) && p() && e('3');

// 测试步骤4：包含特殊字符的步骤导出处理
$case4 = new stdclass();
$case4->id = 4;
$case4->stepDesc = '';
$case4->stepExpect = '';
$specialSteps = array(
    4 => array(
        (object)array('id' => 8, 'parent' => 0, 'desc' => '包含"引号"的步骤', 'expect' => '包含"引号"的预期')
    )
);
r($caselibTest->processStepForExportTest($case4, $specialSteps, array('fileType' => 'csv'), 'has_csv_escape')) && p() && e('1');

// 测试步骤5：HTML格式导出的换行符处理
$case5 = new stdclass();
$case5->id = 3;
$case5->stepDesc = '';
$case5->stepExpect = '';
$htmlSteps = array(
    3 => array(
        (object)array('id' => 5, 'parent' => 0, 'desc' => '步骤1', 'expect' => '预期1'),
        (object)array('id' => 6, 'parent' => 0, 'desc' => '步骤2', 'expect' => '预期2')
    )
);
r($caselibTest->processStepForExportTest($case5, $htmlSteps, array('fileType' => 'html'), 'has_stepDesc')) && p() && e('1');