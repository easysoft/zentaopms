#!/usr/bin/env php
<?php

/**

title=测试 treeModel::buildTree();
timeout=0
cid=19344

- 步骤1：case类型但无对象数据时返回false @0
- 步骤2：story类型模块构建tree正常情况属性id @1
- 步骤3：不存在的rootID时返回false @0
- 步骤4：传入数组形式extra参数属性name @测试模块1
- 步骤5：传入空数组形式extra参数属性parent @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$moduleTable = zenData('module');
$moduleTable->loadYaml('module_buildtree', false, 2)->gen(15);

$storyTable = zenData('story');
$storyTable->loadYaml('story_buildtree', false, 2)->gen(10);

$caseTable = zenData('case');
$caseTable->loadYaml('case_buildtree', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$treeTest = new treeModelTest();

// 准备测试数据
$testModule = new stdClass();
$testModule->id = 1;
$testModule->root = 1;
$testModule->parent = 0;
$testModule->name = '测试模块1';
$testModule->path = ',1,';

$testModule2 = new stdClass();
$testModule2->id = 6;
$testModule2->root = 2;
$testModule2->parent = 0;
$testModule2->name = '测试模块2';
$testModule2->path = ',6,';

$testModule3 = new stdClass();
$testModule3->id = 99;
$testModule3->root = 99;
$testModule3->parent = 0;
$testModule3->name = '测试模块3';
$testModule3->path = ',99,';

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($treeTest->buildTreeTest($testModule2, 'case', '0', array('treeModel', 'createCaseLink'), array('rootID' => 2, 'branch' => 1), 'null')) && p() && e('0'); // 步骤1：case类型但无对象数据时返回false
r($treeTest->buildTreeTest($testModule, 'story', '0', array('treeModel', 'createStoryLink'), array('rootID' => 1, 'branch' => 0), 'all')) && p('id') && e('1'); // 步骤2：story类型模块构建tree正常情况
r($treeTest->buildTreeTest($testModule3, 'case', '0', array('treeModel', 'createCaseLink'), array('rootID' => 99, 'branch' => 0), 'null')) && p() && e('0'); // 步骤3：不存在的rootID时返回false
r($treeTest->buildTreeTest($testModule, 'story', '0', array('treeModel', 'createStoryLink'), array('testParam' => 'value'), 'test')) && p('name') && e('测试模块1'); // 步骤4：传入数组形式extra参数
r($treeTest->buildTreeTest($testModule, 'story', '0', array('treeModel', 'createStoryLink'), array(), 'branch1')) && p('parent') && e('0'); // 步骤5：传入空数组形式extra参数