#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createWorkflowStatus();
timeout=0
cid=15853

- 步骤1:测试open版本下返回序列化的空数组 @a:0:{}
- 步骤2:测试企业版无zentaoStatus相关键返回原始relations @a:1:{s:8:"otherKey";s:9:"testValue";}
- 步骤3:测试企业版添加测试用例状态(add_case_status) @a:2:{s:12:"zentaoObject";a:1:{i:10001;s:8:"testcase";}s:17:"zentaoStatus10001";a:1:{s:7:"status1";s:7:"status1";}}
- 步骤4:测试企业版添加工作流状态(add_flow_status) @a:2:{s:12:"zentaoObject";a:1:{i:10002;s:10:"customflow";}s:17:"zentaoStatus10002";a:1:{s:7:"status2";s:7:"status2";}}
- 步骤5:测试企业版混合处理多个状态 @a:3:{s:12:"zentaoObject";a:2:{i:10003;s:8:"testcase";i:10004;s:10:"customflow";}s:17:"zentaoStatus10003";a:1:{s:7:"status3";s:7:"status3";}s:17:"zentaoStatus10004";a:1:{s:7:"status4";s:7:"status4";}}

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备
zenData('workflowfield')->gen(0);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$convertTest = new convertTaoTest();

// 5. 测试步骤
global $config;
$originalEdition = $config->edition;

// 步骤1: 测试open版本下返回原始relations
$config->edition = 'open';
r($convertTest->createWorkflowStatusTest(array())) && p() && e('a:0:{}'); // 步骤1:测试open版本下返回序列化的空数组

// 步骤2: 测试企业版无zentaoStatus相关键返回原始relations
$config->edition = 'max';
r($convertTest->createWorkflowStatusTest(array('otherKey' => 'testValue'))) && p() && e('a:1:{s:8:"otherKey";s:9:"testValue";}'); // 步骤2:测试企业版无zentaoStatus相关键返回原始relations

// 步骤3: 测试企业版添加测试用例状态(add_case_status)
$config->edition = 'max';
$relations3 = array(
    'zentaoObject' => array('10001' => 'testcase'),
    'zentaoStatus10001' => array('status1' => 'add_case_status')
);
r($convertTest->createWorkflowStatusTest($relations3)) && p() && e('a:2:{s:12:"zentaoObject";a:1:{i:10001;s:8:"testcase";}s:17:"zentaoStatus10001";a:1:{s:7:"status1";s:7:"status1";}}'); // 步骤3:测试企业版添加测试用例状态(add_case_status)

// 步骤4: 测试企业版添加工作流状态(add_flow_status)
$config->edition = 'max';
$relations4 = array(
    'zentaoObject' => array('10002' => 'customflow'),
    'zentaoStatus10002' => array('status2' => 'add_flow_status')
);
r($convertTest->createWorkflowStatusTest($relations4)) && p() && e('a:2:{s:12:"zentaoObject";a:1:{i:10002;s:10:"customflow";}s:17:"zentaoStatus10002";a:1:{s:7:"status2";s:7:"status2";}}'); // 步骤4:测试企业版添加工作流状态(add_flow_status)

// 步骤5: 测试企业版混合处理多个状态
$config->edition = 'max';
$relations5 = array(
    'zentaoObject' => array('10003' => 'testcase', '10004' => 'customflow'),
    'zentaoStatus10003' => array('status3' => 'add_case_status'),
    'zentaoStatus10004' => array('status4' => 'add_flow_status')
);
r($convertTest->createWorkflowStatusTest($relations5)) && p() && e('a:3:{s:12:"zentaoObject";a:2:{i:10003;s:8:"testcase";i:10004;s:10:"customflow";}s:17:"zentaoStatus10003";a:1:{s:7:"status3";s:7:"status3";}s:17:"zentaoStatus10004";a:1:{s:7:"status4";s:7:"status4";}}'); // 步骤5:测试企业版混合处理多个状态

// 恢复版本设置
$config->edition = $originalEdition;