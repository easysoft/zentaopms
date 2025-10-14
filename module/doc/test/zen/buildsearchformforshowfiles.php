#!/usr/bin/env php
<?php

/**

title=测试 docZen::buildSearchFormForShowFiles();
timeout=0
cid=0

- 步骤1：产品类型搜索表单配置
 - 属性methodExists @yes
 - 属性paramTypes @valid
 - 属性typeValid @yes
 - 属性hasSpecificTypes @yes
- 步骤2：项目类型搜索表单配置
 - 属性methodExists @yes
 - 属性paramTypes @valid
 - 属性typeValid @yes
 - 属性hasSpecificTypes @yes
- 步骤3：执行类型搜索表单配置
 - 属性methodExists @yes
 - 属性paramTypes @valid
 - 属性typeValid @yes
 - 属性hasSpecificTypes @yes
- 步骤4：方法存在性和参数类型验证
 - 属性methodExists @yes
 - 属性paramTypes @valid
 - 属性typeValid @yes
 - 属性hasRequiredTypes @yes
- 步骤5：搜索表单基本配置验证
 - 属性configSet @yes
 - 属性objectTypeSet @yes
 - 属性objectTypeCount @12
 - 属性moduleName @projectDocFile

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zenData('user')->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($docTest->buildSearchFormForShowFilesTest('product', 1, '', 0)) && p('methodExists,paramTypes,typeValid,hasSpecificTypes') && e('yes,valid,yes,yes'); // 步骤1：产品类型搜索表单配置
r($docTest->buildSearchFormForShowFilesTest('project', 1, '', 0)) && p('methodExists,paramTypes,typeValid,hasSpecificTypes') && e('yes,valid,yes,yes'); // 步骤2：项目类型搜索表单配置
r($docTest->buildSearchFormForShowFilesTest('execution', 1, '', 0)) && p('methodExists,paramTypes,typeValid,hasSpecificTypes') && e('yes,valid,yes,yes'); // 步骤3：执行类型搜索表单配置
r($docTest->buildSearchFormForShowFilesTest('product', 2, 'list', 5)) && p('methodExists,paramTypes,typeValid,hasRequiredTypes') && e('yes,valid,yes,yes'); // 步骤4：方法存在性和参数类型验证
r($docTest->buildSearchFormForShowFilesTest('project', 3, 'grid', 10)) && p('configSet,objectTypeSet,objectTypeCount,moduleName') && e('yes,yes,12,projectDocFile'); // 步骤5：搜索表单基本配置验证