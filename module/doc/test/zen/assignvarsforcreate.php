#!/usr/bin/env php
<?php

/**

title=测试 docZen::assignVarsForCreate();
timeout=0
cid=0

- 步骤1：正常产品类型创建参数
 - 属性objectType @product
 - 属性spaceType @product
 - 属性type @product
 - 属性libID @1
 - 属性objectID @1
 - 属性docType @html
- 步骤2：项目类型创建参数
 - 属性objectType @project
 - 属性spaceType @project
 - 属性type @project
 - 属性libID @6
 - 属性objectID @2
 - 属性docType @word
- 步骤3：执行类型创建参数
 - 属性objectType @execution
 - 属性spaceType @execution
 - 属性type @execution
 - 属性libID @11
 - 属性docType @ppt
- 步骤4：自定义类型创建参数
 - 属性objectType @custom
 - 属性spaceType @custom
 - 属性type @custom
 - 属性libID @16
 - 属性docType @excel
- 步骤5：我的空间类型创建参数
 - 属性objectType @mine
 - 属性spaceType @mine
 - 属性type @mine
 - 属性libID @19
 - 属性docType @attachment
 - 属性moduleID @5

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('doclib');
$table->id->range('1-20');
$table->type->range('product{5},project{5},execution{5},custom{3},mine{2}');
$table->name->range('产品文档库1,产品文档库2,产品文档库3,产品文档库4,产品文档库5,项目文档库1,项目文档库2,项目文档库3,项目文档库4,项目文档库5,执行文档库1,执行文档库2,执行文档库3,执行文档库4,执行文档库5,自定义文档库1,自定义文档库2,自定义文档库3,我的文档库1,我的文档库2');
$table->product->range('1-5{5},0{15}');
$table->project->range('0{5},1-5{5},1-5{5},0{5}');
$table->execution->range('0{20}');
$table->acl->range('open{10},private{5},default{5}');
$table->vision->range('rnd');
$table->addedBy->range('admin');
$table->gen(20);

$productTable = zenData('product');
$productTable->id->range('1-5');
$productTable->name->range('产品1,产品2,产品3,产品4,产品5');
$productTable->status->range('normal');
$productTable->gen(5);

$projectTable = zenData('project');
$projectTable->id->range('1-5');
$projectTable->name->range('项目1,项目2,项目3,项目4,项目5');
$projectTable->status->range('doing');
$projectTable->type->range('project');
$projectTable->gen(5);

// execution表数据暂时不生成，因为表可能不存在

$userTable = zenData('user');
$userTable->id->range('1-5');
$userTable->account->range('admin,user1,user2,user3,user4');
$userTable->realname->range('管理员,用户1,用户2,用户3,用户4');
$userTable->gen(5);

$groupTable = zenData('group');
$groupTable->id->range('1-3');
$groupTable->name->range('管理员,开发,测试');
$groupTable->gen(3);

$moduleTable = zenData('module');
$moduleTable->id->range('1-10');
$moduleTable->root->range('1-5{2}');
$moduleTable->name->range('模块1,模块2,模块3,模块4,模块5,子模块1,子模块2,子模块3,子模块4,子模块5');
$moduleTable->type->range('doc');
$moduleTable->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($docTest->assignVarsForCreateTest('product', 1, 1, 1, 'html')) && p('objectType,spaceType,type,libID,objectID,docType') && e('product,product,product,1,1,html'); // 步骤1：正常产品类型创建参数
r($docTest->assignVarsForCreateTest('project', 2, 6, 2, 'word')) && p('objectType,spaceType,type,libID,objectID,docType') && e('project,project,project,6,2,word'); // 步骤2：项目类型创建参数
r($docTest->assignVarsForCreateTest('execution', 3, 11, 3, 'ppt')) && p('objectType,spaceType,type,libID,docType') && e('execution,execution,execution,11,ppt'); // 步骤3：执行类型创建参数
r($docTest->assignVarsForCreateTest('custom', 0, 16, 4, 'excel')) && p('objectType,spaceType,type,libID,docType') && e('custom,custom,custom,16,excel'); // 步骤4：自定义类型创建参数
r($docTest->assignVarsForCreateTest('mine', 0, 19, 5, 'attachment')) && p('objectType,spaceType,type,libID,docType,moduleID') && e('mine,mine,mine,19,attachment,5'); // 步骤5：我的空间类型创建参数