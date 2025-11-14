#!/usr/bin/env php
<?php

/**

title=测试 executionZen::correctErrorLang();
timeout=0
cid=16423

- 步骤1：基础语言修正功能测试
 - 属性execution_team @团队成员
 - 属性error_unique @『%s』已经有『%s』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。
- 步骤2：project tab下语言替换测试
 - 属性project_name @项目名称
 - 属性project_code @项目代号
- 步骤3：非project tab下语言替换测试
 - 属性project_name @项目名称
 - 属性project_code @项目代号
- 步骤4：requiredFields语言重新定义测试
 - 属性execution_team @团队成员
 - 属性error_unique @『%s』已经有『%s』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。
- 步骤5：空配置时的安全处理测试
 - 属性execution_team @团队成员
 - 属性error_unique @『%s』已经有『%s』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。
- 步骤6：其他场景的语言修正测试
 - 属性execution_team @团队成员
 - 属性error_unique @『%s』已经有『%s』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$executionTest = new executionZenTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($executionTest->correctErrorLangTest('')) && p('execution_team,error_unique') && e('团队成员,『%s』已经有『%s』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 步骤1：基础语言修正功能测试
r($executionTest->correctErrorLangTest('project')) && p('project_name,project_code') && e('项目名称,项目代号'); // 步骤2：project tab下语言替换测试
r($executionTest->correctErrorLangTest('execution')) && p('project_name,project_code') && e('项目名称,项目代号'); // 步骤3：非project tab下语言替换测试  
r($executionTest->correctErrorLangTest('project')) && p('execution_team,error_unique') && e('团队成员,『%s』已经有『%s』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 步骤4：requiredFields语言重新定义测试
r($executionTest->correctErrorLangTest('')) && p('execution_team,error_unique') && e('团队成员,『%s』已经有『%s』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 步骤5：空配置时的安全处理测试
r($executionTest->correctErrorLangTest('other')) && p('execution_team,error_unique') && e('团队成员,『%s』已经有『%s』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 步骤6：其他场景的语言修正测试