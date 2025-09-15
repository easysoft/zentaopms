#!/usr/bin/env php
<?php

/**

title=测试 docZen::assignVarsForMySpace();
timeout=0
cid=0

- 步骤1：正常情况
 - 属性type @mine
 - 属性libID @1
 - 属性browseType @all
 - 属性spaceType @mine
 - 属性canExport @0
- 步骤2：不同libID和moduleID
 - 属性libID @2
 - 属性moduleID @5
 - 属性browseType @byModule
 - 属性canUpdateOrder @1
- 步骤3：团队空间类型测试
 - 属性type @custom
 - 属性libID @0
 - 属性objectTitle @团队空间
 - 属性spaceType @mine
- 步骤4：复杂参数测试
 - 属性objectID @0
 - 属性param @10
 - 属性orderBy @addedDate_asc
 - 属性objectTitle @个人空间
- 步骤5：检查库类型属性libType @lib

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('doclib');
$table->id->range('1-10');
$table->type->range('mine{3},custom{3},product{2},project{2}');
$table->name->range('我的文档库,团队文档库,产品文档库,项目文档库');
$table->acl->range('private{3},open{4},default{3}');
$table->vision->range('rnd');
$table->gen(10);

$docTable = zenData('doc');
$docTable->id->range('1-5');
$docTable->lib->range('1-5');
$docTable->title->range('文档1,文档2,文档3,文档4,文档5');
$docTable->type->range('text');
$docTable->status->range('normal');
$docTable->gen(5);

$userTable = zenData('user');
$userTable->id->range('1-3');
$userTable->account->range('admin,user1,user2');
$userTable->realname->range('管理员,用户1,用户2');
$userTable->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($docTest->assignVarsForMySpaceTest('mine', 0, 1, 0, 'all', 0, 'id_desc', array(), new stdClass(), array(), '我的空间')) && p('type,libID,browseType,spaceType,canExport') && e('mine,1,all,mine,0'); // 步骤1：正常情况
r($docTest->assignVarsForMySpaceTest('mine', 0, 2, 5, 'byModule', 0, 'order_asc', array(), new stdClass(), array(), '文档库')) && p('libID,moduleID,browseType,canUpdateOrder') && e('2,5,byModule,1'); // 步骤2：不同libID和moduleID
r($docTest->assignVarsForMySpaceTest('custom', 0, 0, 0, 'all', 0, 'title_desc', array(), new stdClass(), array(), '团队空间')) && p('type,libID,objectTitle,spaceType') && e('custom,0,团队空间,mine'); // 步骤3：团队空间类型测试
r($docTest->assignVarsForMySpaceTest('mine', 1, 3, 2, 'draft', 10, 'addedDate_asc', array(), new stdClass(), array(), '个人空间')) && p('objectID,param,orderBy,objectTitle') && e('0,10,addedDate_asc,个人空间'); // 步骤4：复杂参数测试
r($docTest->assignVarsForMySpaceTest('mine', 0, 1, 0, 'all', 0, 'id_desc', array(), new stdClass(), array(), '我的文档')) && p('libType') && e('lib'); // 步骤5：检查库类型