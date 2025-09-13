#!/usr/bin/env php
<?php

/**

title=测试 docZen::responseAfterCreateLib();
timeout=0
cid=0

- 步骤1：产品类型测试属性result @success
- 步骤2：项目类型测试属性result @success
- 步骤3：执行类型测试属性result @success
- 步骤4：自定义类型测试属性result @success
- 步骤5：个人类型测试属性result @success

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备
$doclib = zenData('doclib');
$doclib->loadYaml('doclib_responseaftercreatelib', false, 2);
$doclib->gen(10);

// 创建action表数据用于测试action记录
$action = zenData('action');
$action->id->range('1-100');
$action->objectType->range('docLib');
$action->objectID->range('1-10');
$action->action->range('Created');
$action->actor->range('admin');
$action->date->range('`2023-01-01 00:00:00`');
$action->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$docTest = new docTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($docTest->responseAfterCreateLibTest('product', 1, 1, '产品文档库', 'id_asc')) && p('result') && e('success'); // 步骤1：产品类型测试
r($docTest->responseAfterCreateLibTest('project', 11, 2, '项目文档库', 'id_desc')) && p('result') && e('success'); // 步骤2：项目类型测试
r($docTest->responseAfterCreateLibTest('execution', 101, 3, '执行文档库', 'order_asc')) && p('result') && e('success'); // 步骤3：执行类型测试
r($docTest->responseAfterCreateLibTest('custom', 0, 4, '自定义文档库', '')) && p('result') && e('success'); // 步骤4：自定义类型测试
r($docTest->responseAfterCreateLibTest('mine', 0, 5, '我的文档库', '')) && p('result') && e('success'); // 步骤5：个人类型测试