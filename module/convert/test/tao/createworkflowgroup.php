#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createWorkflowGroup();
timeout=0
cid=15852

- 步骤1：开源版本直接返回原始relations @a:1:{s:4:"test";s:4:"data";}
- 步骤2：企业版无项目关系时返回原始relations @a:1:{s:4:"test";s:4:"data";}
- 步骤3：企业版有项目关系无产品关系 @a:1:{s:4:"test";s:4:"data";}
- 步骤4：企业版完整关系下创建工作流组 @a:1:{s:4:"test";s:4:"data";}
- 步骤5：已存在工作流组关系时跳过 @a:1:{s:4:"test";s:4:"data";}

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备 - 简化的数据准备，避免复杂数据库操作

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$convertTest = new convertTaoTest();

// 5. 测试步骤
r($convertTest->createWorkflowGroupTest(array('test' => 'data'), array(), array(), 'open')) && p() && e('a:1:{s:4:"test";s:4:"data";}'); // 步骤1：开源版本直接返回原始relations
r($convertTest->createWorkflowGroupTest(array('test' => 'data'), array(), array(), 'biz')) && p() && e('a:1:{s:4:"test";s:4:"data";}'); // 步骤2：企业版无项目关系时返回原始relations
r($convertTest->createWorkflowGroupTest(array('test' => 'data'), array(1 => 1), array(), 'biz')) && p() && e('a:1:{s:4:"test";s:4:"data";}'); // 步骤3：企业版有项目关系无产品关系
r($convertTest->createWorkflowGroupTest(array('test' => 'data'), array(1 => 1, 2 => 2), array(1 => 1), 'biz')) && p() && e('a:1:{s:4:"test";s:4:"data";}'); // 步骤4：企业版完整关系下创建工作流组
r($convertTest->createWorkflowGroupTest(array('test' => 'data'), array(1 => 1), array(), 'biz', array(1 => 1))) && p() && e('a:1:{s:4:"test";s:4:"data";}'); // 步骤5：已存在工作流组关系时跳过

/* 清理本用例创建的流程数据，避免残留空 table 的 workflow 污染 objectTables。 */
zenData('workflow')->gen(0);
zenData('workflowaction')->gen(0);
zenData('workflowfield')->gen(0);
zenData('workflowlayout')->gen(0);
zenData('workflowlabel')->gen(0);
zenData('workflowrule')->gen(0);
zenData('workflowdatasource')->gen(0);
zenData('workflowrelation')->gen(0);
zenData('workflowlinkdata')->gen(0);
zenData('workflowversion')->gen(0);
zenData('workflowreport')->gen(0);
zenData('workflowsql')->gen(0);
