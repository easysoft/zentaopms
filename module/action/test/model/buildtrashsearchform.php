#!/usr/bin/env php
<?php

/**

title=测试 actionModel::buildTrashSearchForm();
timeout=0
cid=14879

- 步骤1：正常情况属性actionURL @action-trash-browseTrash-all-byQuery-123.html
- 步骤2：queryID为0边界值属性queryID @0
- 步骤3：空字符串actionURL属性actionURL @~~
- 步骤4：包含特殊字符的actionURL属性queryID @789
- 步骤5：负数queryID属性actionURL @action-trash-browseTrash-all-byQuery--1.html

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$actionTest = new actionModelTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($actionTest->buildTrashSearchFormTest(123, 'action-trash-browseTrash-all-byQuery-123.html')) && p('actionURL') && e('action-trash-browseTrash-all-byQuery-123.html'); // 步骤1：正常情况
r($actionTest->buildTrashSearchFormTest(0, 'action-trash-browseTrash-all-byQuery-0.html')) && p('queryID') && e('0'); // 步骤2：queryID为0边界值
r($actionTest->buildTrashSearchFormTest(456, '')) && p('actionURL') && e('~~'); // 步骤3：空字符串actionURL
r($actionTest->buildTrashSearchFormTest(789, 'action-trash-browseTrash-all-byQuery-789.html?param=value&other=test')) && p('queryID') && e('789'); // 步骤4：包含特殊字符的actionURL
r($actionTest->buildTrashSearchFormTest(-1, 'action-trash-browseTrash-all-byQuery--1.html')) && p('actionURL') && e('action-trash-browseTrash-all-byQuery--1.html'); // 步骤5：负数queryID