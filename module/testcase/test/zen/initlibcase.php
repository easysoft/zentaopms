#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::initLibCase();
timeout=0
cid=0

- 步骤1：正常情况
 - 属性lib @1
 - 属性title @测试用例标题
 - 属性precondition @前置条件
 - 属性keywords @关键词
 - 属性pri @1
 - 属性type @功能
 - 属性stage @功能测试阶段
 - 属性status @normal
 - 属性fromCaseID @1
 - 属性fromCaseVersion @1
 - 属性color @#FF0000
 - 属性order @100
- 步骤2：新增库用例验证openedBy属性openedBy @admin
- 步骤3：更新已存在的库用例
 - 属性id @5
 - 属性lastEditedBy @admin
 - 属性version @3
- 步骤4：模块为空的处理属性module @0
- 步骤5：来源用例信息设置
 - 属性fromCaseID @1
 - 属性fromCaseVersion @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$caseTable = zenData('case');
$caseTable->id->range('1-10');
$caseTable->title->range('测试用例{1-10}');
$caseTable->precondition->range('前置条件{1-10}');
$caseTable->keywords->range('关键词{1-10}');
$caseTable->pri->range('1-4');
$caseTable->type->range('功能,性能,配置,安装,安全');
$caseTable->stage->range('功能测试阶段,集成测试阶段');
$caseTable->status->range('normal,wait,blocked');
$caseTable->version->range('1-3');
$caseTable->color->range('#FF0000,#00FF00,#0000FF');
$caseTable->module->range('1-5,0{2}');
$caseTable->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseTest();

// 5. 准备测试数据
$case = new stdclass();
$case->id = 1;
$case->title = '测试用例标题';
$case->precondition = '前置条件';
$case->keywords = '关键词';
$case->pri = 1;
$case->type = '功能';
$case->stage = '功能测试阶段';
$case->status = 'normal';
$case->version = 1;
$case->color = '#FF0000';
$case->module = 1;

$libID = 1;
$maxOrder = 100;
$maxModuleOrder = 50;
$libCases = array();

$existingCase = clone $case;
$existingCase->id = 2;
$libCasesWithExisting = array(2 => array(5 => (object)array('version' => 2)));

$emptyModuleCase = clone $case;
$emptyModuleCase->module = 0;

$caseWithModule = clone $case;
$caseWithModule->module = 5;

// 强制要求：必须包含至少5个测试步骤
r($testcaseTest->initLibCaseTest($case, $libID, $maxOrder, $maxModuleOrder, $libCases)) && p('lib,title,precondition,keywords,pri,type,stage,status,fromCaseID,fromCaseVersion,color,order') && e('1,测试用例标题,前置条件,关键词,1,功能,功能测试阶段,normal,1,1,#FF0000,100'); // 步骤1：正常情况
r($testcaseTest->initLibCaseTest($case, $libID, $maxOrder, $maxModuleOrder, $libCases)) && p('openedBy') && e('admin'); // 步骤2：新增库用例验证openedBy
r($testcaseTest->initLibCaseTest($existingCase, $libID, $maxOrder, $maxModuleOrder, $libCasesWithExisting)) && p('id,lastEditedBy,version') && e('5,admin,3'); // 步骤3：更新已存在的库用例
r($testcaseTest->initLibCaseTest($emptyModuleCase, $libID, $maxOrder, $maxModuleOrder, $libCases)) && p('module') && e('0'); // 步骤4：模块为空的处理
r($testcaseTest->initLibCaseTest($case, $libID, $maxOrder, $maxModuleOrder, $libCases)) && p('fromCaseID,fromCaseVersion') && e('1,1'); // 步骤5：来源用例信息设置