#!/usr/bin/env php
<?php

/**

title=测试 apiZen::parseDocSpaceParam();
timeout=0
cid=15126

- 步骤1：无cookie情况
 - 属性type @custom
 - 属性objectID @1
 - 属性libID @1
- 步骤2：product类型cookie
 - 属性type @product
 - 属性objectID @2
 - 属性libID @2
- 步骤3：project类型cookie
 - 属性type @project
 - 属性objectID @3
 - 属性libID @3
- 步骤4：无效cookie处理
 - 属性type @custom
 - 属性objectID @1
 - 属性libID @1
- 步骤5：边界条件处理
 - 属性type @custom
 - 属性objectID @999
 - 属性libID @999

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('doclib');
$table->type->range('custom{3},product{3},project{2},api{2}');
$table->product->range('0{6},1{2},2{1},3{1}');
$table->project->range('0{6},1{2},2{1},3{1}');
$table->name->range('接口文档库{1},产品文档库{2},项目文档库{2},自定义文档库{3},测试文档库{2}');
$table->acl->range('open{5},private{3},custom{2}');
$table->deleted->range('0');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$apiTest = new apiZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$libs = array(1 => (object)array('id' => 1, 'name' => '测试文档库', 'product' => 0, 'project' => 0));
r($apiTest->parseDocSpaceParamTest($libs, 1, 'custom', 1, 1, 'custom', 1, '')) && p('type,objectID,libID') && e('custom,1,1'); // 步骤1：无cookie情况

$cookieProduct = '{"type":"product","objectID":2,"libID":2,"moduleID":2,"browseType":"all","param":"test"}';
r($apiTest->parseDocSpaceParamTest($libs, 1, 'custom', 1, 1, 'custom', 1, $cookieProduct)) && p('type,objectID,libID') && e('product,2,2'); // 步骤2：product类型cookie

$cookieProject = '{"type":"project","objectID":3,"libID":3,"moduleID":3,"browseType":"bymodule","param":""}';
r($apiTest->parseDocSpaceParamTest($libs, 1, 'custom', 1, 1, 'custom', 1, $cookieProject)) && p('type,objectID,libID') && e('project,3,3'); // 步骤3：project类型cookie

$cookieInvalid = '{"type":"product","objectID":0}';
r($apiTest->parseDocSpaceParamTest($libs, 1, 'custom', 1, 1, 'custom', 1, $cookieInvalid)) && p('type,objectID,libID') && e('custom,1,1'); // 步骤4：无效cookie处理

$cookieBoundary = '{"type":"custom","objectID":999,"libID":999,"moduleID":0,"browseType":"all","param":""}';
r($apiTest->parseDocSpaceParamTest($libs, 1, 'custom', 1, 1, 'custom', 1, $cookieBoundary)) && p('type,objectID,libID') && e('custom,999,999'); // 步骤5：边界条件处理