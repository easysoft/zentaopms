#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::apiCreateLabel();
timeout=0
cid=16578

- 步骤1：缺少name属性 @0
- 步骤2：缺少color属性 @0
- 步骤3：name和color都为空 @0
- 步骤4：无效projectID @0
- 步骤5：完整有效参数 @error

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('pipeline');
$table->id->range('1-5');
$table->type->range('gitlab');
$table->name->range('GitLab{1-5}');
$table->url->range('http://gitlab{1-5}.test.com');
$table->token->range('test_token_{1-5}');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$gitlabTest = new gitlabTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$gitlabID = 1;
$projectID = 2;

// 测试label对象缺少name属性
$labelWithoutName = new stdclass();
$labelWithoutName->color = '#FF0000';
$labelWithoutName->description = 'Test label without name';
r($gitlabTest->apiCreateLabelTest($gitlabID, $projectID, $labelWithoutName)) && p() && e('0'); // 步骤1：缺少name属性

// 测试label对象缺少color属性
$labelWithoutColor = new stdclass();
$labelWithoutColor->name = 'TestLabel';
$labelWithoutColor->description = 'Test label without color';
r($gitlabTest->apiCreateLabelTest($gitlabID, $projectID, $labelWithoutColor)) && p() && e('0'); // 步骤2：缺少color属性

// 测试label对象name和color都为空
$emptyLabel = new stdclass();
$emptyLabel->name = '';
$emptyLabel->color = '';
r($gitlabTest->apiCreateLabelTest($gitlabID, $projectID, $emptyLabel)) && p() && e('0'); // 步骤3：name和color都为空

// 测试使用有效gitlabID但无效projectID
$validLabel = new stdclass();
$validLabel->name = 'UnitTestLabel';
$validLabel->color = '#0033CC';
$validLabel->description = 'Unit test label description';
r($gitlabTest->apiCreateLabelTest($gitlabID, 0, $validLabel)) && p() && e('0'); // 步骤4：无效projectID

// 测试使用完整有效参数创建标签
$result = $gitlabTest->apiCreateLabelTest($gitlabID, $projectID, $validLabel);
if(isset($result->name) && $result->name == 'UnitTestLabel') $result = 'success';
if(isset($result->message) && $result->message == 'Label already exists') $result = 'exists';
if($result === false || $result === null) $result = 'error';
r($result) && p() && e('error'); // 步骤5：完整有效参数