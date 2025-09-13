#!/usr/bin/env php
<?php

/**

title=测试 ciZen::getProductIdAndJobID();
timeout=0
cid=0

- 执行ciTest模块的getProductIdAndJobIDTest方法，参数是$params1, $post1 
 -  @1
 - 属性1 @1
- 执行ciTest模块的getProductIdAndJobIDTest方法，参数是$params2, $post2 
 -  @99
 - 属性1 @2
- 执行ciTest模块的getProductIdAndJobIDTest方法，参数是$params3, $post3 
 -  @88
 - 属性1 @0
- 执行ciTest模块的getProductIdAndJobIDTest方法，参数是$params4, $post4 
 -  @0
 - 属性1 @0
- 执行ciTest模块的getProductIdAndJobIDTest方法，参数是$params5, $post5 
 -  @1
 - 属性1 @0
- 执行ciTest模块的getProductIdAndJobIDTest方法，参数是$params6, $post6 
 -  @1
 - 属性1 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ci.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$jobTable = zenData('job');
$jobTable->id->range('1-5');
$jobTable->name->range('job1,job2,job3,job4,job5');
$jobTable->product->range('1-5');
$jobTable->frame->range('phpunit,pytest,junit');
$jobTable->engine->range('jenkins,gitlab');
$jobTable->gen(5);

$compileTable = zenData('compile');
$compileTable->id->range('1-5');
$compileTable->name->range('compile1,compile2,compile3,compile4,compile5');
$compileTable->job->range('1-5');
$compileTable->status->range('success,fail,create_fail,timeout');
$compileTable->gen(5);

$caseTable = zenData('case');
$caseTable->id->range('1-5');
$caseTable->product->range('1-5');
$caseTable->title->range('Test case 1,Test case 2,Test case 3,Test case 4,Test case 5');
$caseTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$ciTest = new ciTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
// 步骤1：测试通过compileID获取productID和jobID的正常情况
$params1 = array('compile' => 1);
$post1 = new stdClass();
$post1->testType = 'unit';
$post1->productId = 0;
r($ciTest->getProductIdAndJobIDTest($params1, $post1)) && p('0,1') && e('1,1');

// 步骤2：测试当productID在post中已提供时的情况
$params2 = array('compile' => 2);
$post2 = new stdClass();
$post2->testType = 'unit';
$post2->productId = 99;
r($ciTest->getProductIdAndJobIDTest($params2, $post2)) && p('0,1') && e('99,2');

// 步骤3：测试当testType为func且productID为空时从funcResult获取
$params3 = array('compile' => 0);
$post3 = new stdClass();
$post3->testType = 'func';
$post3->productId = 0;
$funcResult = array();
$funcResult[0] = new stdClass();
$funcResult[0]->productId = 88;
$funcResult[0]->id = 1;
$post3->funcResult = $funcResult;
r($ciTest->getProductIdAndJobIDTest($params3, $post3)) && p('0,1') && e('88,0');

// 步骤4：测试当compileID为0且productID为0时的情况
$params4 = array('compile' => 0);
$post4 = new stdClass();
$post4->testType = 'unit';
$post4->productId = 0;
r($ciTest->getProductIdAndJobIDTest($params4, $post4)) && p('0,1') && e('0,0');

// 步骤5：测试当funcResult中的用例没有productId但有id时从testcase获取
$params5 = array('compile' => 0);
$post5 = new stdClass();
$post5->testType = 'func';
$post5->productId = 0;
$funcResult5 = array();
$funcResult5[0] = new stdClass();
$funcResult5[0]->productId = 0;
$funcResult5[0]->id = 1;
$post5->funcResult = $funcResult5;
r($ciTest->getProductIdAndJobIDTest($params5, $post5)) && p('0,1') && e('1,0');

// 步骤6：测试边界情况：compile状态为running需要同步
$params6 = array('compile' => 1);
$post6 = new stdClass();
$post6->testType = 'unit';
$post6->productId = 0;
// 由于我们无法直接测试syncCompileStatus的调用，这里测试基本功能
r($ciTest->getProductIdAndJobIDTest($params6, $post6)) && p('0,1') && e('1,1');