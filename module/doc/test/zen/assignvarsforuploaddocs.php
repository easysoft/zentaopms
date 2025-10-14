#!/usr/bin/env php
<?php

/**

title=测试 docZen::assignVarsForUploadDocs();
timeout=0
cid=0

- 步骤1：正常project类型
 - 属性objectType @project
 - 属性linkType @project
 - 属性libID @1
- 步骤2：mine类型空间
 - 属性objectType @mine
 - 属性linkType @mine
 - 属性moduleID @1
- 步骤3：custom类型空间
 - 属性objectType @custom
 - 属性linkType @custom
- 步骤4：无效libID处理
 - 属性objectType @project
 - 属性linkType @project
 - 属性libID @0
- 步骤5：产品空间配置
 - 属性objectType @product
 - 属性linkType @product
 - 属性moduleID @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$doclib = zenData('doclib');
$doclib->id->range('1-5');
$doclib->type->range('project{2},product,custom,mine');
$doclib->name->range('项目文档库1,项目文档库2,产品文档库,团队文档库,我的文档库');
$doclib->acl->range('open');
$doclib->addedBy->range('admin');
$doclib->deleted->range('0');
$doclib->gen(5);

$doc = zenData('doc');
$doc->id->range('1-10');
$doc->lib->range('1-5:R');
$doc->module->range('0{5},1-5');
$doc->title->range('文档标题1,文档标题2,章节标题1,章节标题2');
$doc->type->range('text{5},html{5}');
$doc->status->range('normal');
$doc->parent->range('0{8},1-2');
$doc->deleted->range('0');
$doc->gen(10);

$module = zenData('module');
$module->id->range('1-5');
$module->root->range('1-5');
$module->type->range('doc');
$module->name->range('模块1,模块2,分类1,分类2,目录1');
$module->parent->range('0{3},1-2');
$module->grade->range('1{3},2{2}');
$module->deleted->range('0');
$module->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($docTest->assignVarsForUploadDocsTest('project', 1, 1, 0, 'html')) && p('objectType,linkType,libID') && e('project,project,1'); // 步骤1：正常project类型
r($docTest->assignVarsForUploadDocsTest('mine', 0, 5, 1, 'attachment')) && p('objectType,linkType,moduleID') && e('mine,mine,1'); // 步骤2：mine类型空间
r($docTest->assignVarsForUploadDocsTest('custom', 0, 4, 0, 'word')) && p('objectType,linkType') && e('custom,custom'); // 步骤3：custom类型空间
r($docTest->assignVarsForUploadDocsTest('project', 1, 0, 0, '')) && p('objectType,linkType,libID') && e('project,project,0'); // 步骤4：无效libID处理
r($docTest->assignVarsForUploadDocsTest('product', 1, 2, 2, 'excel')) && p('objectType,linkType,moduleID') && e('product,product,2'); // 步骤5：产品空间配置
