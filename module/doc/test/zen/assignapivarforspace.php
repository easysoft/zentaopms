#!/usr/bin/env php
<?php

/**

title=测试 docZen::assignApiVarForSpace();
timeout=0
cid=0

- 步骤1：api类型且搜索模式
 - 属性apiID @0
 - 属性release @0
- 步骤2：api类型且正常浏览
 - 属性apiID @0
 - 属性release @0
- 步骤3：doc类型且搜索模式属性canExport @0
- 步骤4：doc类型且正常浏览属性canExport @0
- 步骤5：权限和分页测试属性apiLibID @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
su('admin');

$doclib = zenData('doclib');
$doclib->id->range('1-10');
$doclib->type->range('api{3},custom{3},product{2},project{2}');
$doclib->vision->range('rnd{10}');
$doclib->name->range('API库{3},自定义库{3},产品库{2},项目库{2}');
$doclib->acl->range('open{6},private{2},custom{2}');
$doclib->addedBy->range('admin{5},user1{3},user2{2}');
$doclib->gen(10);

$doc = zenData('doc');
$doc->id->range('1-15');
$doc->lib->range('1-5,1-5,1-5');
$doc->title->range('API文档{5},产品文档{5},项目文档{5}');
$doc->type->range('text{15}');
$doc->status->range('normal{12},draft{3}');
$doc->addedBy->range('admin{8},user1{4},user2{3}');
$doc->gen(15);

$user = zenData('user');
$user->id->range('1-5');
$user->account->range('admin,user1,user2,user3,user4');
$user->realname->range('管理员,用户1,用户2,用户3,用户4');
$user->role->range('admin{1},dev{2},qa{1},pm{1}');
$user->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$docTest = new docTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($docTest->assignApiVarForSpaceTest('product', 'bySearch', 'api', 1, array(1 => 'API库'), 1, 0, 1, 'id_desc', 0, 10, 20, 1)) && p('apiID,release') && e('0,0'); // 步骤1：api类型且搜索模式
r($docTest->assignApiVarForSpaceTest('product', 'all', 'api', 2, array(2 => 'API库2'), 1, 1, 0, 'id_desc', 0, 5, 10, 1)) && p('apiID,release') && e('0,0'); // 步骤2：api类型且正常浏览
r($docTest->assignApiVarForSpaceTest('project', 'bySearch', 'doc', 3, array(3 => '项目库'), 2, 0, 2, 'title_asc', 0, 15, 30, 2)) && p('canExport') && e('0'); // 步骤3：doc类型且搜索模式
r($docTest->assignApiVarForSpaceTest('custom', 'all', 'doc', 4, array(4 => '自定义库'), 0, 2, 0, 'addedDate_desc', 1, 8, 15, 1)) && p('canExport') && e('0'); // 步骤4：doc类型且正常浏览
r($docTest->assignApiVarForSpaceTest('product', 'draft', 'api', 1, array(1 => 'API库'), 1, 0, 0, 'id_asc', 0, 3, 5, 1)) && p('apiLibID') && e('1'); // 步骤5：权限和分页测试