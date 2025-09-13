#!/usr/bin/env php
<?php

/**

title=测试 docZen::getAllSpaces();
timeout=0
cid=0

- 步骤1：默认参数获取所有空间（个人空间+团队空间）属性mine @我的空间
- 步骤2：参数包含'doctemplate'获取文档模板空间 @0
- 步骤3：参数包含'nomine'获取团队空间（不包含个人空间）属性3 @团队空间
- 步骤4：参数包含'onlymine'仅获取个人空间属性mine @我的空间
- 步骤5：参数为空字符串的默认行为验证属性mine @我的空间

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('doclib');
$table->id->range('1-20');
$table->type->range('mine{2},custom{3},product{3},project{3},api{2}');
$table->vision->range('rnd{10},lite{10}');
$table->name->range('我的空间{2},团队空间{3},产品文档库{3},项目文档库{3},API文档库{2}');
$table->main->range('1{5},0{15}');
$table->acl->range('private{5},open{8},default{7}');
$table->addedBy->range('admin,user1,user2,user3,user4');
$table->addedDate->range('`2024-01-01 00:00:00`');
$table->deleted->range('0{18},1{2}');
$table->gen(15);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($docTest->getAllSpacesTest('')) && p('mine') && e('我的空间'); // 步骤1：默认参数获取所有空间（个人空间+团队空间）
r($docTest->getAllSpacesTest('doctemplate')) && p() && e('0'); // 步骤2：参数包含'doctemplate'获取文档模板空间
r($docTest->getAllSpacesTest('nomine')) && p('3') && e('团队空间'); // 步骤3：参数包含'nomine'获取团队空间（不包含个人空间）
r($docTest->getAllSpacesTest('onlymine')) && p('mine') && e('我的空间'); // 步骤4：参数包含'onlymine'仅获取个人空间
r($docTest->getAllSpacesTest()) && p('mine') && e('我的空间'); // 步骤5：参数为空字符串的默认行为验证