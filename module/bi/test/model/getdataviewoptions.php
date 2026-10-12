#!/usr/bin/env php
<?php

/**

title=测试 biModel::getDataviewOptions();
timeout=0
cid=15165

- 步骤1：正常获取bug对象status字段的选项 @1
- 步骤2：正常获取bug对象type字段的选项 @1
- 步骤3：获取不存在对象的字段选项 @1
- 步骤4：获取存在对象但不存在字段的选项 @1
- 步骤5：获取字段配置中没有options属性的字段 @1
- 步骤6：路径穿越的对象名不加载任何文件 @1
- 步骤7：带扩展名的对象名不加载任何文件 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$biTest = new biModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r(is_array($biTest->getDataviewOptionsTest('bug', 'status'))) && p() && e('1'); // 步骤1：正常情况-获取bug状态选项
r(is_array($biTest->getDataviewOptionsTest('bug', 'type'))) && p() && e('1'); // 步骤2：正常情况-获取bug类型选项
r(is_array($biTest->getDataviewOptionsTest('nonexistent', 'field'))) && p() && e('1'); // 步骤3：不存在的对象
r(is_array($biTest->getDataviewOptionsTest('bug', 'nonexistentfield'))) && p() && e('1'); // 步骤4：存在对象但不存在字段
r(is_array($biTest->getDataviewOptionsTest('bug', 'title'))) && p() && e('1'); // 步骤5：存在字段但无options属性
r($biTest->getDataviewOptionsTest('../../../../../tmp/vuln014_marker', 'demo') === array()) && p() && e('1'); // 步骤6：路径穿越的对象名不加载任何文件
r($biTest->getDataviewOptionsTest('bug.php', 'status') === array()) && p() && e('1'); // 步骤7：带扩展名的对象名不加载任何文件
