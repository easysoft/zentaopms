#!/usr/bin/env php
<?php

/**

title=测试 userModel::checkProductPriv();
timeout=0
cid=19589

- 步骤1：系统管理员权限检查 @1
- 步骤2：产品审批人权限检查 @1
- 步骤3：产品PMT权限检查 @1
- 步骤4：普通用户访问开放产品 @1
- 步骤5：产品PO权限检查 @1
- 步骤6：产品QD权限检查 @1
- 步骤7：产品RD权限检查 @1
- 步骤8：产品创建者权限检查 @1
- 步骤9：产品反馈负责人权限检查 @1
- 步骤10：产品工单负责人权限检查 @1
- 步骤11：产品干系人权限检查 @1
- 步骤12：产品团队成员权限检查 @1
- 步骤13：产品白名单用户权限检查 @1
- 步骤14：产品管理员权限检查 @1
- 步骤15：普通用户访问私有产品 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
$table = zenData('product');
$table->id->range('1-5');
$table->name->range('产品1,产品2,产品3,产品4,产品5');
$table->code->range('product1,product2,product3,product4,product5');
$table->PO->range('po1,po2,po3,po4,po5');
$table->QD->range('qd1,qd2,qd3,qd4,qd5');
$table->RD->range('rd1,rd2,rd3,rd4,rd5');
$table->acl->range('open,private,custom,open,private');
$table->reviewer->range('reviewer1,reviewer2,,reviewer4,');
$table->PMT->range('pmt1,pmt2,,pmt4,');
$table->feedback->range('feedback1,feedback2,,feedback4,');
$table->ticket->range('ticket1,ticket2,,ticket4,');
$table->createdBy->range('creator1,creator2,creator3,creator4,creator5');
$table->gen(5);

$companyTable = zenData('company');
$companyTable->admins->range('admin1,admin2');
$companyTable->gen(1);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$userTest = new userModelTest();

// 5. 执行测试（必须包含至少5个测试步骤）
// 创建测试数据对象
$product1 = new stdClass();
$product1->id = 1;
$product1->name = '产品1';
$product1->acl = 'open';
$product1->PO = 'po1';
$product1->QD = 'qd1';
$product1->RD = 'rd1';
$product1->reviewer = 'reviewer1';
$product1->PMT = 'pmt1';
$product1->feedback = 'feedback1';
$product1->ticket = 'ticket1';
$product1->createdBy = 'creator1';

$product2 = new stdClass();
$product2->id = 2;
$product2->name = '产品2';
$product2->acl = 'private';
$product2->PO = 'po2';
$product2->QD = 'qd2';
$product2->RD = 'rd2';
$product2->reviewer = 'reviewer2';
$product2->PMT = 'pmt2';
$product2->feedback = 'feedback2';
$product2->ticket = 'ticket2';
$product2->createdBy = 'creator2';

$teams = array('team1' => 'team1', 'team2' => 'team2');
$stakeholders = array('stakeholder1' => 'stakeholder1', 'stakeholder2' => 'stakeholder2');
$whiteList = array('whitelist1' => 'whitelist1', 'whitelist2' => 'whitelist2');
$admins = array('productadmin1' => 'productadmin1', 'productadmin2' => 'productadmin2');

r($userTest->checkProductPrivTest($product1, 'admin1', $teams, $stakeholders, $whiteList, $admins)) && p() && e('1'); // 步骤1：系统管理员权限检查
r($userTest->checkProductPrivTest($product1, 'reviewer1', $teams, $stakeholders, $whiteList, $admins)) && p() && e('1'); // 步骤2：产品审批人权限检查
r($userTest->checkProductPrivTest($product1, 'pmt1', $teams, $stakeholders, $whiteList, $admins)) && p() && e('1'); // 步骤3：产品PMT权限检查
r($userTest->checkProductPrivTest($product1, 'normaluser', $teams, $stakeholders, $whiteList, $admins)) && p() && e('1'); // 步骤4：普通用户访问开放产品
r($userTest->checkProductPrivTest($product1, 'po1', $teams, $stakeholders, $whiteList, $admins)) && p() && e('1'); // 步骤5：产品PO权限检查
r($userTest->checkProductPrivTest($product1, 'qd1', $teams, $stakeholders, $whiteList, $admins)) && p() && e('1'); // 步骤6：产品QD权限检查
r($userTest->checkProductPrivTest($product1, 'rd1', $teams, $stakeholders, $whiteList, $admins)) && p() && e('1'); // 步骤7：产品RD权限检查
r($userTest->checkProductPrivTest($product1, 'creator1', $teams, $stakeholders, $whiteList, $admins)) && p() && e('1'); // 步骤8：产品创建者权限检查
r($userTest->checkProductPrivTest($product1, 'feedback1', $teams, $stakeholders, $whiteList, $admins)) && p() && e('1'); // 步骤9：产品反馈负责人权限检查
r($userTest->checkProductPrivTest($product1, 'ticket1', $teams, $stakeholders, $whiteList, $admins)) && p() && e('1'); // 步骤10：产品工单负责人权限检查
r($userTest->checkProductPrivTest($product2, 'stakeholder1', $teams, $stakeholders, $whiteList, $admins)) && p() && e('1'); // 步骤11：产品干系人权限检查
r($userTest->checkProductPrivTest($product2, 'team1', $teams, $stakeholders, $whiteList, $admins)) && p() && e('1'); // 步骤12：产品团队成员权限检查
r($userTest->checkProductPrivTest($product2, 'whitelist1', $teams, $stakeholders, $whiteList, $admins)) && p() && e('1'); // 步骤13：产品白名单用户权限检查
r($userTest->checkProductPrivTest($product2, 'productadmin1', $teams, $stakeholders, $whiteList, $admins)) && p() && e('1'); // 步骤14：产品管理员权限检查
r($userTest->checkProductPrivTest($product2, 'normaluser', $teams, $stakeholders, $whiteList, $admins)) && p() && e('0'); // 步骤15：普通用户访问私有产品