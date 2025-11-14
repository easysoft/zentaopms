#!/usr/bin/env php
<?php

/**

title=测试 aiModel::saveMiniProgramFields();
timeout=0
cid=15061

- 执行aiTest模块的saveMiniProgramFieldsTest方法，参数是1, $testData1  @2
- 执行aiTest模块的saveMiniProgramFieldsTest方法，参数是2, $testData2  @1
- 执行aiTest模块的saveMiniProgramFieldsTest方法，参数是3, $testData3  @1
- 执行aiTest模块的saveMiniProgramFieldsTest方法，参数是4, $testData4  @0
- 执行aiTest模块的saveMiniProgramFieldsTest方法，参数是999, $testData5  @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

// 2. zendata数据准备（简化版本）
$miniProgramTable = zenData('ai_miniprogram');
$miniProgramTable->id->range('1-3');
$miniProgramTable->name->range('测试小程序1,测试小程序2,测试小程序3');
$miniProgramTable->category->range('personal,work,other');
$miniProgramTable->desc->range('描述1,描述2,描述3');
$miniProgramTable->icon->range('icon1,icon2,icon3');
$miniProgramTable->createdBy->range('admin');
$miniProgramTable->createdDate->range('`2023-01-01 10:00:00`');
$miniProgramTable->editedBy->range('admin');
$miniProgramTable->editedDate->range('`2023-01-01 10:00:00`');
$miniProgramTable->published->range('0');
$miniProgramTable->deleted->range('0');
$miniProgramTable->prompt->range('提示词1,提示词2,提示词3');
$miniProgramTable->builtIn->range('0');
$miniProgramTable->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$aiTest = new aiTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 测试步骤1：保存包含prompt和fields的正常数据
$testData1 = new stdClass();
$testData1->prompt = '更新后的提示词模板';
$testData1->fields = array(
    (object)array('appID' => 1, 'name' => '姓名', 'type' => 'text', 'placeholder' => '请输入姓名', 'options' => null, 'required' => '1'),
    (object)array('appID' => 1, 'name' => '年龄', 'type' => 'text', 'placeholder' => '请输入年龄', 'options' => null, 'required' => '1')
);
r($aiTest->saveMiniProgramFieldsTest(1, $testData1)) && p() && e('2');

// 测试步骤2：保存只有fields没有prompt的数据
$testData2 = new stdClass();
$testData2->fields = array(
    (object)array('appID' => 2, 'name' => '职业', 'type' => 'text', 'placeholder' => '请输入职业', 'options' => null, 'required' => '0')
);
r($aiTest->saveMiniProgramFieldsTest(2, $testData2)) && p() && e('1');

// 测试步骤3：保存包含options数组的字段数据
$testData3 = new stdClass();
$testData3->fields = array(
    (object)array('appID' => 3, 'name' => '学历', 'type' => 'radio', 'placeholder' => null, 'options' => array('本科', '硕士', '博士'), 'required' => '1')
);
r($aiTest->saveMiniProgramFieldsTest(3, $testData3)) && p() && e('1');

// 测试步骤4：保存空的fields数据
$testData4 = new stdClass();
$testData4->fields = array();
r($aiTest->saveMiniProgramFieldsTest(4, $testData4)) && p() && e('0');

// 测试步骤5：使用不存在的appID保存数据
$testData5 = new stdClass();
$testData5->prompt = '测试不存在ID的提示词';
$testData5->fields = array(
    (object)array('appID' => 999, 'name' => '测试字段', 'type' => 'text', 'placeholder' => '测试', 'options' => null, 'required' => '0')
);
r($aiTest->saveMiniProgramFieldsTest(999, $testData5)) && p() && e('1');