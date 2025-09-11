#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createWorkflowGroup();
timeout=0
cid=0

- 步骤1：开源版本 @a:1:{s:4:"test";s:4:"data";}
- 步骤2：企业版无项目关系 @a:1:{s:4:"test";s:4:"data";}
- 步骤3：企业版有项目关系无产品关系 @a:1:{s:4:"test";s:4:"data";}
- 步骤4：企业版完整关系 @a:1:{s:4:"test";s:4:"data";}
- 步骤5：已存在工作流组关系 @a:1:{s:4:"test";s:4:"data";}

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. zendata数据准备
$table = zenData('jiratmprelation');
$table->AID->range('1-10');
$table->AType->range('jproject{5},zworkflowgroup{5}');
$table->BID->range('1-10');
$table->BType->range('zproject{5},zworkflowgroup{5}');
$table->gen(10);

$workflowGroupTable = zenData('workflowgroup');
$workflowGroupTable->id->range('1-5');
$workflowGroupTable->type->range('project{3},product{2}');
$workflowGroupTable->name->range('测试工作流组1,测试工作流组2,测试工作流组3,测试工作流组4,测试工作流组5');
$workflowGroupTable->status->range('normal{5}');
$workflowGroupTable->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$convertTest = new convertTest();

// 5. 测试步骤
r($convertTest->createWorkflowGroupTest(array('test' => 'data'), array(), array(), 'open')) && p() && e('a:1:{s:4:"test";s:4:"data";}'); // 步骤1：开源版本
r($convertTest->createWorkflowGroupTest(array('test' => 'data'), array(), array(), 'biz')) && p() && e('a:1:{s:4:"test";s:4:"data";}'); // 步骤2：企业版无项目关系
r($convertTest->createWorkflowGroupTest(array('test' => 'data'), array(1 => 1), array(), 'biz')) && p() && e('a:1:{s:4:"test";s:4:"data";}'); // 步骤3：企业版有项目关系无产品关系
r($convertTest->createWorkflowGroupTest(array('test' => 'data'), array(1 => 1, 2 => 2), array(1 => 1), 'biz')) && p() && e('a:1:{s:4:"test";s:4:"data";}'); // 步骤4：企业版完整关系
r($convertTest->createWorkflowGroupTest(array('test' => 'data'), array(1 => 1), array(), 'biz', array(1 => 1))) && p() && e('a:1:{s:4:"test";s:4:"data";}'); // 步骤5：已存在工作流组关系