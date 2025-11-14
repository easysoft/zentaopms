#!/usr/bin/env php
<?php

/**

title=测试 productZen::getFormFields4Edit();
timeout=0
cid=17586

- 步骤1:正常产品编辑-program字段为select控件第program条的control属性 @select
- 步骤2:name字段应该是必填的第name条的required属性 @1
- 步骤3:name字段默认值应该是产品名称第name条的default属性 @产品3
- 步骤4:changeProjects应该是hidden控件第changeProjects条的control属性 @hidden
- 步骤5:PO字段默认值应该是产品的PO第PO条的default属性 @user4

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备
$table = zenData('product');
$table->id->range('1-10');
$table->program->range('1-5');
$table->name->range('产品1,产品2,产品3,产品4,产品5');
$table->code->range('code1,code2,code3,code4,code5');
$table->PO->range('admin,user1,user2,user3,user4');
$table->QD->range('admin,user1,user2,user3,user4');
$table->RD->range('admin,user1,user2,user3,user4');
$table->type->range('normal,branch,platform');
$table->status->range('normal,closed');
$table->acl->range('open,private,custom');
$table->gen(10);

$programTable = zenData('project');
$programTable->id->range('1-10');
$programTable->name->range('项目集1,项目集2,项目集3,项目集4,项目集5');
$programTable->type->range('program');
$programTable->parent->range('0');
$programTable->grade->range('1');
$programTable->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$productTest = new productZenTest();
global $tester;
$product1 = $tester->loadModel('product')->getByID(1);
$product2 = $tester->loadModel('product')->getByID(2);
$product3 = $tester->loadModel('product')->getByID(3);
$product4 = $tester->loadModel('product')->getByID(4);
$product5 = $tester->loadModel('product')->getByID(5);

// 5. 测试步骤:必须至少5个
r($productTest->getFormFields4EditTest($product1)) && p('program:control') && e('select'); // 步骤1:正常产品编辑-program字段为select控件
r($productTest->getFormFields4EditTest($product2)) && p('name:required') && e('1'); // 步骤2:name字段应该是必填的
r($productTest->getFormFields4EditTest($product3)) && p('name:default') && e('产品3'); // 步骤3:name字段默认值应该是产品名称
r($productTest->getFormFields4EditTest($product4)) && p('changeProjects:control') && e('hidden'); // 步骤4:changeProjects应该是hidden控件
r($productTest->getFormFields4EditTest($product5)) && p('PO:default') && e('user4'); // 步骤5:PO字段默认值应该是产品的PO