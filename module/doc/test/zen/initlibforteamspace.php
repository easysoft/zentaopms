#!/usr/bin/env php
<?php

/**

title=测试 docZen::initLibForTeamSpace();
timeout=0
cid=0

- 步骤1：正常创建团队空间库属性type @custom
- 步骤2：已存在时不重复创建属性result @exists
- 步骤3：验证文档库ACL属性属性acl @open
- 步骤4：验证vision配置属性vision @rnd
- 步骤5：验证创建者属性addedBy @admin

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备（简单配置，不生成具体数据）

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($docTest->initLibForTeamSpaceTest()) && p('type') && e('custom'); // 步骤1：正常创建团队空间库
r($docTest->initLibForTeamSpaceTest()) && p('result') && e('exists'); // 步骤2：已存在时不重复创建
r($docTest->getTeamLibAttributesTest()) && p('acl') && e('open'); // 步骤3：验证文档库ACL属性
r($docTest->getTeamLibAttributesTest()) && p('vision') && e('rnd'); // 步骤4：验证vision配置
r($docTest->getTeamLibAttributesTest()) && p('addedBy') && e('admin'); // 步骤5：验证创建者