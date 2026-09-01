#!/usr/bin/env php
<?php

/**

title=测试 userModel::getLatestUserView();
timeout=0
cid=19614

- 执行userTest模块的getLatestUserViewTest方法，参数是'user1', '2, 3', $project1, 'project', array  @2,3,1

- 执行userTest模块的getLatestUserViewTest方法，参数是'user1', '2, 3', $product1, 'product', array  @2,3,1

- 执行userTest模块的getLatestUserViewTest方法，参数是'user1', '1, 2, 3', $project2, 'project', array  @1,3

- 执行userTest模块的getLatestUserViewTest方法，参数是'user1', '1, 2, 3', $product2, 'product', array  @1,3

- 执行userTest模块的getLatestUserViewTest方法，参数是'user1', '101, 102, 103', $program1, 'program', array  @101,102,103

- 执行userTest模块的getLatestUserViewTest方法，参数是'admin', '1, 2', $project3, 'project', array  @1,2,3

- 执行userTest模块的getLatestUserViewTest方法，参数是'user1', '101, 103', $program2, 'program', array  @101,103

- 执行userTest模块的getLatestUserViewTest方法，参数是'user1', '', $project4, 'project', array  @,4

- 执行userTest模块的getLatestUserViewTest方法，参数是'user1', '1, 2, 3', $sprint1, 'sprint', array  @1,2,3,5

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$user = zenData('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2,pm1,pm2,po1,po2,dev1,dev2,tester1');
$user->realname->range('管理员,用户1,用户2,项目经理1,项目经理2,产品经理1,产品经理2,开发1,开发2,测试1');
$user->role->range('admin,user{9}');
$user->password->range('123456{10}');
$user->deleted->range('0{10}');
$user->gen(10);

$company = zenData('company');
$company->id->range('1');
$company->name->range('测试公司');
$company->admins->range('`,admin,`');
$company->gen(1);

// 直接通过DAO更新数据库确保admins字段格式正确
global $tester;
$tester->dao->update(TABLE_COMPANY)->set('admins')->eq(',admin,')->where('id')->eq(1)->exec();

$project = zenData('project');
$project->id->range('1-5');
$project->name->range('项目1,项目2,项目3,项目4,项目5');
$project->type->range('project{5}');
$project->PM->range('pm1,pm2,admin,user1,user2');
$project->openedBy->range('admin,user1,user2,pm1,pm2');
$project->acl->range('open,private,custom,open,private');
$project->deleted->range('0{5}');
$project->gen(5);

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->type->range('normal{5}');
$product->PO->range('po1,po2,admin,user1,user2');
$product->QD->range('po1,po2,admin,user1,user2');
$product->RD->range('dev1,dev2,admin,user1,user2');
$product->PMT->range('pm1,pm2,admin,user1,user2');
$product->createdBy->range('admin,user1,user2,po1,po2');
$product->acl->range('open,private,custom,open,private');
$product->deleted->range('0{5}');
$product->gen(5);

$program = zenData('project');
$program->id->range('101-105');
$program->name->range('项目集1,项目集2,项目集3,项目集4,项目集5');
$program->type->range('program{5}');
$program->PM->range('pm1,pm2,admin,user1,user2');
$program->openedBy->range('admin,user1,user2,pm1,pm2');
$program->acl->range('open,private,program,open,private');
$program->parent->range('0{5}');
$program->deleted->range('0{5}');
$program->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$userTest = new userModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：用户有权限访问项目，权限ID不在视图列表中，期望添加到视图列表
$project1 = new stdClass();
$project1->id = 1;
$project1->PM = 'user1';
$project1->PO = '';
$project1->QD = '';
$project1->RD = '';
$project1->openedBy = 'admin';
$project1->acl = 'open';
$project1->type = 'project';
$project1->parent = 0;
$project1->path = ',1,';
$project1->deleted = '0';
r($userTest->getLatestUserViewTest('user1', '2,3', $project1, 'project', array(), array(1 => array('user1')), array(), array())) && p() && e('2,3,1');

// 步骤2：用户有权限访问产品，权限ID不在视图列表中，期望添加到视图列表
$product1 = new stdClass();
$product1->id = 1;
$product1->PO = 'user1';
$product1->QD = 'user2';
$product1->RD = 'dev1';
$product1->PMT = 'pm1';
$product1->reviewer = 'admin';
$product1->createdBy = 'user1';
$product1->acl = 'open';
$product1->deleted = '0';
r($userTest->getLatestUserViewTest('user1', '2,3', $product1, 'product', array(1 => array('user1')), array(1 => array('user1')), array(), array())) && p() && e('2,3,1');

// 步骤3：用户无权限访问项目，权限ID在视图列表中，期望从视图列表移除
$project2 = new stdClass();
$project2->id = 2;
$project2->PM = 'pm1';
$project2->PO = '';
$project2->QD = '';
$project2->RD = '';
$project2->openedBy = 'pm2';
$project2->acl = 'private';
$project2->type = 'project';
$project2->parent = 0;
$project2->path = ',2,';
$project2->deleted = '0';
r($userTest->getLatestUserViewTest('user1', '1,2,3', $project2, 'project', array(), array(), array(), array())) && p() && e('1,3');

// 步骤4：用户无权限访问产品，权限ID在视图列表中，期望从视图列表移除
$product2 = new stdClass();
$product2->id = 2;
$product2->PO = 'po1';
$product2->QD = 'po2';
$product2->RD = 'dev1';
$product2->PMT = 'pm1';
$product2->reviewer = 'admin';
$product2->createdBy = 'po1';
$product2->acl = 'private';
$product2->deleted = '0';
r($userTest->getLatestUserViewTest('user1', '1,2,3', $product2, 'product', array(), array(), array(), array())) && p() && e('1,3');

// 步骤5：用户有权限访问项目集，但ID已在视图列表中，期望视图列表保持不变
$program1 = new stdClass();
$program1->id = 101;
$program1->PM = 'user1';
$program1->openedBy = 'admin';
$program1->acl = 'open';
$program1->parent = 0;
$program1->deleted = '0';
r($userTest->getLatestUserViewTest('user1', '101,102,103', $program1, 'program', array(101 => array('user1')), array(), array(), array())) && p() && e('101,102,103');

// 步骤6：管理员用户访问私有项目，期望获得访问权限并添加到视图列表
$project3 = new stdClass();
$project3->id = 3;
$project3->PM = 'admin';
$project3->PO = '';
$project3->QD = '';
$project3->RD = '';
$project3->openedBy = 'admin';
$project3->acl = 'private';
$project3->type = 'project';
$project3->parent = 0;
$project3->path = ',3,';
$project3->deleted = '0';
r($userTest->getLatestUserViewTest('admin', '1,2', $project3, 'project', array(), array(), array(), array())) && p() && e('1,2,3');

// 步骤7：普通用户访问有ACL限制的项目集，期望根据项目集权限规则进行权限判断
$program2 = new stdClass();
$program2->id = 102;
$program2->PM = 'pm1';
$program2->openedBy = 'pm2';
$program2->acl = 'program';
$program2->parent = 0;
$program2->path = ',102,';
$program2->deleted = '0';
r($userTest->getLatestUserViewTest('user1', '101,103', $program2, 'program', array(), array(), array(), array())) && p() && e('101,103');

// 步骤8：测试空视图列表的情况，期望正确处理空字符串
$project4 = new stdClass();
$project4->id = 4;
$project4->PM = 'user1';
$project4->PO = '';
$project4->QD = '';
$project4->RD = '';
$project4->openedBy = 'admin';
$project4->acl = 'open';
$project4->type = 'project';
$project4->parent = 0;
$project4->path = ',4,';
$project4->deleted = '0';
r($userTest->getLatestUserViewTest('user1', '', $project4, 'project', array(), array(4 => array('user1')), array(), array())) && p() && e(',4');

// 步骤9：测试迭代(sprint)对象的权限控制，期望按项目权限规则处理
$sprint1 = new stdClass();
$sprint1->id = 5;
$sprint1->PM = 'user1';
$sprint1->PO = '';
$sprint1->QD = '';
$sprint1->RD = '';
$sprint1->openedBy = 'admin';
$sprint1->acl = 'open';
$sprint1->type = 'sprint';
$sprint1->parent = 1;
$sprint1->path = ',1,5,';
$sprint1->deleted = '0';
r($userTest->getLatestUserViewTest('user1', '1,2,3', $sprint1, 'sprint', array(), array(5 => array('user1')), array(), array())) && p() && e('1,2,3,5');
