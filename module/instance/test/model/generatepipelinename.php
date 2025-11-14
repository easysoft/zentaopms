#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::generatePipelineName();
timeout=0
cid=16796

- 步骤1：新名称不存在，返回原名称 @newapp
- 步骤2：名称存在但版本不同，返回名称+版本 @testapp-2.0.0
- 步骤3：名称和版本都存在，返回名称+数字 @testapp-1
- 步骤4：空名称处理，返回0或null @0
- 步骤5：不同chart类型的处理 @uniqueapp

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/instance.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// 手动插入测试数据
global $tester;
$tester->dao->delete()->from(TABLE_PIPELINE)->exec();
$tester->dao->insert(TABLE_PIPELINE)->data(array(
    'type' => 'gitlab',
    'name' => 'testapp',
    'url' => 'http://test1.com',
    'account' => 'admin',
    'password' => 'password123',
    'token' => 'token123',
    'private' => 'private123',
    'createdBy' => 'system',
    'createdDate' => '2024-01-01 10:00:00',
    'editedBy' => 'system',
    'editedDate' => '2024-01-01 11:00:00',
    'deleted' => '0'
))->exec();
$tester->dao->insert(TABLE_PIPELINE)->data(array(
    'type' => 'gitlab',
    'name' => 'testapp-1.0.0',
    'url' => 'http://test2.com',
    'account' => 'admin',
    'password' => 'password123',
    'token' => 'token123',
    'private' => 'private123',
    'createdBy' => 'system',
    'createdDate' => '2024-01-01 10:00:00',
    'editedBy' => 'system',
    'editedDate' => '2024-01-01 11:00:00',
    'deleted' => '0'
))->exec();

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$instanceTest = new instanceTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
// 创建测试实例对象
$instance1 = new stdclass;
$instance1->name = 'newapp';
$instance1->chart = 'gitlab';
$instance1->appVersion = '1.5.0';

$instance2 = new stdclass;
$instance2->name = 'testapp';
$instance2->chart = 'gitlab';
$instance2->appVersion = '2.0.0';

$instance3 = new stdclass;
$instance3->name = 'testapp';
$instance3->chart = 'gitlab';
$instance3->appVersion = '1.0.0';

$instance4 = new stdclass;
$instance4->name = '';
$instance4->chart = 'jenkins';
$instance4->appVersion = '1.0.0';

$instance5 = new stdclass;
$instance5->name = 'uniqueapp';
$instance5->chart = 'sonarqube';
$instance5->appVersion = '3.0.0';

r($instanceTest->generatePipelineNameTest($instance1)) && p() && e('newapp'); // 步骤1：新名称不存在，返回原名称
r($instanceTest->generatePipelineNameTest($instance2)) && p() && e('testapp-2.0.0'); // 步骤2：名称存在但版本不同，返回名称+版本
r($instanceTest->generatePipelineNameTest($instance3)) && p() && e('testapp-1'); // 步骤3：名称和版本都存在，返回名称+数字
r($instanceTest->generatePipelineNameTest($instance4)) && p() && e('0'); // 步骤4：空名称处理，返回0或null
r($instanceTest->generatePipelineNameTest($instance5)) && p() && e('uniqueapp'); // 步骤5：不同chart类型的处理