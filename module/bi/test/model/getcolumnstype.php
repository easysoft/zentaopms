#!/usr/bin/env php
<?php

/**

title=测试 biModel::getColumnsType();
timeout=0
cid=0

- 步骤1：正常情况测试ID字段类型属性id @number
- 步骤2：指定MySQL驱动测试account字段类型属性account @string
- 步骤3：测试多个字段类型
 - 属性id @number
 - 属性account @string
- 步骤4：无结果查询测试属性id @number
- 步骤5：聚合函数字段类型测试属性total @string

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('user');
$table->id->range('1-10');
$table->account->range('admin,user1,user2,user3,test{1},qa{1},dev{1},pm{1},po{1},td{1}');
$table->realname->range('管理员,用户1,用户2,用户3,测试{1},QA{1},开发{1},项目经理{1},产品经理{1},测试主管{1}');
$table->role->range('admin,dev{3},qa{3},pm{2},po{1}');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$biTest = new biTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($biTest->getColumnsTypeTest('select id, account, realname from zt_user limit 1')) && p('id') && e('number'); // 步骤1：正常情况测试ID字段类型
r($biTest->getColumnsTypeTest('select account, realname, role from zt_user limit 1', 'mysql')) && p('account') && e('string'); // 步骤2：指定MySQL驱动测试account字段类型
r($biTest->getColumnsTypeTest('select * from zt_user limit 1')) && p('id,account') && e('number,string'); // 步骤3：测试多个字段类型
r($biTest->getColumnsTypeTest('select id, account from zt_user where id = 999')) && p('id') && e('number'); // 步骤4：无结果查询测试
r($biTest->getColumnsTypeTest('select count(*) as total from zt_user')) && p('total') && e('string'); // 步骤5：聚合函数字段类型测试