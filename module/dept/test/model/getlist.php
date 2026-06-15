#!/usr/bin/env php
<?php

/**

title=测试 deptModel::getList();
timeout=0
cid=15973

- 步骤1：正常情况获取部门列表数量 @10
- 步骤2：验证第一条记录ID第0条的id属性 @1
- 步骤3：验证第一条记录名称第0条的name属性 @技术部
- 步骤4：验证第一条记录父级第0条的parent属性 @0
- 步骤5：验证最后一条记录名称第9条的name属性 @测试部

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（使用当前表结构下稳定的字段集）
$table = zenData('dept');
$table->id->range('1-10');
$table->name->range('技术部,产品部,运营部,人事部,财务部,市场部,客服部,行政部,研发部,测试部');
$table->parent->range('0{3},1{3},2{2},3{2}');
$table->path->range(',1,,2,,3,,1,4,,1,5,,1,6,,2,7,,2,8,,3,9,,3,10,');
$table->grade->range('1{3},2{7}');
$table->order->range('10,20,30,40,50,60,70,80,90,100');
$table->manager->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$deptTest = new deptModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($deptTest->getListTest())) && p() && e('10'); // 步骤1：正常情况获取部门列表数量
r($deptTest->getListTest()) && p('0:id') && e('1'); // 步骤2：验证第一条记录ID
r($deptTest->getListTest()) && p('0:name') && e('技术部'); // 步骤3：验证第一条记录名称
r($deptTest->getListTest()) && p('0:parent') && e('0'); // 步骤4：验证第一条记录父级
r($deptTest->getListTest()) && p('9:name') && e('测试部'); // 步骤5：验证最后一条记录名称
