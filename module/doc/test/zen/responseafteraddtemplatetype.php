#!/usr/bin/env php
<?php

/**

title=测试 docZen::responseAfterAddTemplateType();
timeout=0
cid=0

- 步骤1：正常scope参数属性result @success
- 步骤2：边界值scope为0属性result @success
- 步骤3：负数scope参数属性result @success
- 步骤4：大整数scope参数属性result @success
- 步骤5：验证响应结构完整性
 - 属性result @success
 - 属性message @保存成功
 - 属性load @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('doclib');
$table->id->range('1-10');
$table->type->range('mine,custom,product,project');
$table->name->range('我的空间,团队空间,产品文档库,项目文档库');
$table->acl->range('private,open,default');
$table->addedBy->range('admin,user1,user2');
$table->addedDate->range('`2023-01-01 00:00:00`');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($docTest->responseAfterAddTemplateTypeTest(1)) && p('result') && e('success'); // 步骤1：正常scope参数
r($docTest->responseAfterAddTemplateTypeTest(0)) && p('result') && e('success'); // 步骤2：边界值scope为0
r($docTest->responseAfterAddTemplateTypeTest(-1)) && p('result') && e('success'); // 步骤3：负数scope参数
r($docTest->responseAfterAddTemplateTypeTest(999999)) && p('result') && e('success'); // 步骤4：大整数scope参数
r($docTest->responseAfterAddTemplateTypeTest(100)) && p('result,message,load') && e('success,保存成功,1'); // 步骤5：验证响应结构完整性