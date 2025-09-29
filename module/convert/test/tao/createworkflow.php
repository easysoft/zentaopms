#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createWorkflow();
timeout=0
cid=0

- 步骤1：空relations参数在open版本处理 @rray()
- 步骤2：open版本直接返回原始relations第zentaoObject条的10001属性 @add_custom
- 步骤3：测试包含zentaoObject的relations在open版本第zentaoObject条的10003属性 @existing_module
- 步骤4：测试包含多个zentaoObject的relations在open版本
 - 第zentaoObject条的10001属性 @add_custom
 - 第zentaoObject条的zentaoObject:10002属性 @add_custom
- 步骤5：测试包含其他字段的relations在open版本
 - 第zentaoObject条的10004属性 @add_custom
 - 第zentaoObject条的otherField属性 @testValue

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zenData('workflow')->gen(0);

// 3. 用户登录（选择合适角色）
su('admin');

// 5. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 4. 测试步骤 - 所有测试都使用open版本因为这是主要的测试场景
$originalEdition = $config->edition;
$config->edition = 'open';

r($convertTest->createWorkflowTest(array(), array(), array(), array())) && p() && e(array()); // 步骤1：空relations参数在open版本处理
r($convertTest->createWorkflowTest(array('zentaoObject' => array('10001' => 'add_custom')), array('actions' => array('1' => array('name' => 'Create Issue'))), array(), array())) && p('zentaoObject:10001') && e('add_custom'); // 步骤2：open版本直接返回原始relations
r($convertTest->createWorkflowTest(array('zentaoObject' => array('10003' => 'existing_module')), array(), array(), array())) && p('zentaoObject:10003') && e('existing_module'); // 步骤3：测试包含zentaoObject的relations在open版本
r($convertTest->createWorkflowTest(array('zentaoObject' => array('10001' => 'add_custom', '10002' => 'add_custom')), array('actions' => array('1' => array('name' => 'Test Action'))), array('1' => array('name' => 'Fixed')), array('1' => array('name' => 'High')))) && p('zentaoObject:10001,zentaoObject:10002') && e('add_custom,add_custom'); // 步骤4：测试包含多个zentaoObject的relations在open版本
r($convertTest->createWorkflowTest(array('zentaoObject' => array('10004' => 'add_custom'), 'otherField' => 'testValue'), array(), array(), array())) && p('zentaoObject:10004,otherField') && e('add_custom,testValue'); // 步骤5：测试包含其他字段的relations在open版本

// 恢复原始版本设置
$config->edition = $originalEdition;