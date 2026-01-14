#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createWorkflow();
timeout=0
cid=15850

- 步骤1：测试open版本下空relations参数 @0
- 步骤2：测试open版本下包含zentaoObject的relations第zentaoObject条的10001属性 @add_custom
- 步骤3：测试open版本下包含existing_module的relations第zentaoObject条的10003属性 @existing_module
- 步骤4：测试open版本下包含多个zentaoObject的relations第zentaoObject条的10001属性 @add_custom
- 步骤5：测试open版本下包含其他字段的relations属性otherField @testValue

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备
zenData('workflow')->gen(0);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$convertTest = new convertTaoTest();

// 5. 测试步骤 - 设置为open版本测试主要逻辑
global $config;
$originalEdition = $config->edition;
$config->edition = 'open';

r($convertTest->createWorkflowTest(array(), array(), array(), array())) && p() && e('0'); // 步骤1：测试open版本下空relations参数
r($convertTest->createWorkflowTest(array('zentaoObject' => array('10001' => 'add_custom')), array(), array(), array())) && p('zentaoObject:10001') && e('add_custom'); // 步骤2：测试open版本下包含zentaoObject的relations
r($convertTest->createWorkflowTest(array('zentaoObject' => array('10003' => 'existing_module')), array(), array(), array())) && p('zentaoObject:10003') && e('existing_module'); // 步骤3：测试open版本下包含existing_module的relations
r($convertTest->createWorkflowTest(array('zentaoObject' => array('10001' => 'add_custom', '10002' => 'add_custom')), array(), array(), array())) && p('zentaoObject:10001') && e('add_custom'); // 步骤4：测试open版本下包含多个zentaoObject的relations
r($convertTest->createWorkflowTest(array('zentaoObject' => array('10004' => 'add_custom'), 'otherField' => 'testValue'), array(), array(), array())) && p('otherField') && e('testValue'); // 步骤5：测试open版本下包含其他字段的relations

// 恢复版本设置
$config->edition = $originalEdition;