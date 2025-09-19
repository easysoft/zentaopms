#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::processCaseForExport();
timeout=0
cid=0

- 步骤1：正常用例日期格式化属性openedDate @2023-01-01
- 步骤2：正常用例产品处理属性product @产品1(#1)
- 步骤3：空关联数据产品处理属性product @
- 步骤4：零值日期处理属性openedDate @
- 步骤5：完整关联数据处理属性product @产品2(#2)

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备（简化）
$case = zenData('case');
$case->id->range('1-3');
$case->product->range('1{2},2{1}');
$case->gen(3);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$testcaseTest = new testcaseZenTest();

// 准备测试数据
$products = array(1 => '产品1', 2 => '产品2');
$branches = array(0 => '主干', 1 => '分支1');
$users = array('admin' => '管理员', 'user1' => '用户1', 'user2' => '用户2');
$results = array();
$relatedModules = array(1 => '模块1', 2 => '模块2');
$relatedStories = array(1 => '需求1', 2 => '需求2');
$relatedCases = array(2 => '测试用例2', 3 => '测试用例3');
$relatedSteps = array();
$relatedFiles = array();
$relatedScenes = array(1 => '场景1', 2 => '场景2');

// 构造测试用例对象1：正常情况
$case1 = new stdClass();
$case1->id = 1;
$case1->product = 1;
$case1->branch = 0;
$case1->module = 1;
$case1->story = 1;
$case1->scene = 1;
$case1->title = '测试用例1';
$case1->pri = 2;
$case1->type = 'feature';
$case1->status = 'normal';
$case1->openedBy = 'admin';
$case1->openedDate = '2023-01-01 10:00:00';
$case1->lastEditedBy = 'admin';
$case1->lastEditedDate = '2023-01-15 12:00:00';
$case1->lastRunner = 'admin';
$case1->lastRunDate = '2023-02-01 14:00:00';
$case1->lastRunResult = 'pass';
$case1->linkCase = '';
$case1->bugs = 0;
$case1->results = 0;
$case1->stepNumber = 2;
$case1->caseFails = 0;
$case1->stage = 'unittest,feature';

// 构造测试用例对象2：空关联数据
$case2 = new stdClass();
$case2->id = 2;
$case2->product = 999; // 不存在的产品
$case2->branch = 999; // 不存在的分支
$case2->module = 999; // 不存在的模块
$case2->story = 999; // 不存在的需求
$case2->scene = 999; // 不存在的场景
$case2->title = '测试用例2';
$case2->pri = 1;
$case2->type = 'interface';
$case2->status = 'blocked';
$case2->openedBy = 'user1';
$case2->openedDate = '2023-01-02 11:00:00';
$case2->lastEditedBy = 'user1';
$case2->lastEditedDate = '2023-01-16 13:00:00';
$case2->lastRunner = 'user1';
$case2->lastRunDate = '2023-02-02 15:00:00';
$case2->lastRunResult = 'fail';
$case2->linkCase = '';
$case2->bugs = 0;
$case2->results = 0;
$case2->stepNumber = 2;
$case2->caseFails = 0;
$case2->stage = 'integrate';

// 构造测试用例对象3：零值日期
$case3 = new stdClass();
$case3->id = 3;
$case3->product = 2;
$case3->branch = 1;
$case3->module = 2;
$case3->story = 2;
$case3->scene = 2;
$case3->title = '测试用例3';
$case3->pri = 3;
$case3->type = 'config';
$case3->status = 'investigate';
$case3->openedBy = 'user2';
$case3->openedDate = '0000-00-00 00:00:00';
$case3->lastEditedBy = 'user2';
$case3->lastEditedDate = '0000-00-00 00:00:00';
$case3->lastRunner = 'user2';
$case3->lastRunDate = '0000-00-00 00:00:00';
$case3->lastRunResult = 'skip';
$case3->linkCase = '';
$case3->bugs = 0;
$case3->results = 0;
$case3->stepNumber = 2;
$case3->caseFails = 0;
$case3->stage = 'system';

// 构造测试用例对象4：带链接用例
$case4 = new stdClass();
$case4->id = 4;
$case4->product = 1;
$case4->branch = 0;
$case4->module = 1;
$case4->story = 1;
$case4->scene = 1;
$case4->title = '测试用例4';
$case4->pri = 2;
$case4->type = 'feature';
$case4->status = 'normal';
$case4->openedBy = 'admin';
$case4->openedDate = '2023-01-01 10:00:00';
$case4->lastEditedBy = 'admin';
$case4->lastEditedDate = '2023-01-15 12:00:00';
$case4->lastRunner = 'admin';
$case4->lastRunDate = '2023-02-01 14:00:00';
$case4->lastRunResult = 'pass';
$case4->linkCase = '2,3';
$case4->bugs = 0;
$case4->results = 0;
$case4->stepNumber = 2;
$case4->caseFails = 0;
$case4->stage = 'feature,unittest';

// 构造测试用例对象5：完整关联数据
$case5 = new stdClass();
$case5->id = 5;
$case5->product = 2;
$case5->branch = 1;
$case5->module = 2;
$case5->story = 2;
$case5->scene = 2;
$case5->title = '测试用例5';
$case5->pri = 1;
$case5->type = 'interface';
$case5->status = 'blocked';
$case5->openedBy = 'user1';
$case5->openedDate = '2023-01-02 11:00:00';
$case5->lastEditedBy = 'user1';
$case5->lastEditedDate = '2023-01-16 13:00:00';
$case5->lastRunner = 'user1';
$case5->lastRunDate = '2023-02-02 15:00:00';
$case5->lastRunResult = 'fail';
$case5->linkCase = '';
$case5->bugs = 1;
$case5->results = 3;
$case5->stepNumber = 5;
$case5->caseFails = 1;
$case5->stage = 'integrate,system';

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->processCaseForExportTest($case1, $products, $branches, $users, $results, $relatedModules, $relatedStories, $relatedCases, $relatedSteps, $relatedFiles, $relatedScenes)) && p('openedDate') && e('2023-01-01'); // 步骤1：正常用例日期格式化
r($testcaseTest->processCaseForExportTest($case1, $products, $branches, $users, $results, $relatedModules, $relatedStories, $relatedCases, $relatedSteps, $relatedFiles, $relatedScenes)) && p('product') && e('产品1(#1)'); // 步骤2：正常用例产品处理
r($testcaseTest->processCaseForExportTest($case2, $products, $branches, $users, $results, $relatedModules, $relatedStories, $relatedCases, $relatedSteps, $relatedFiles, $relatedScenes)) && p('product') && e(''); // 步骤3：空关联数据产品处理
r($testcaseTest->processCaseForExportTest($case3, $products, $branches, $users, $results, $relatedModules, $relatedStories, $relatedCases, $relatedSteps, $relatedFiles, $relatedScenes)) && p('openedDate') && e(''); // 步骤4：零值日期处理
r($testcaseTest->processCaseForExportTest($case5, $products, $branches, $users, $results, $relatedModules, $relatedStories, $relatedCases, $relatedSteps, $relatedFiles, $relatedScenes)) && p('product') && e('产品2(#2)'); // 步骤5：完整关联数据处理