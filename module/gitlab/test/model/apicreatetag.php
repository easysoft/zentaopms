#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::apiCreateTag();
timeout=0
cid=16582

- 执行$result @return empty
- 执行gitlabTest模块的apiCreateTagTest方法，参数是1, 1, $emptyTagName  @0
- 执行gitlabTest模块的apiCreateTagTest方法，参数是1, 1, $emptyRef  @0
- 执行$result2 @return empty
- 执行$result3 @return empty

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('pipeline');
$table->type->range('gitlab{3}');
$table->name->range('Test GitLab{3}');
$table->url->range('https://gitlab.example.com{3}');
$table->token->range('test-token{3}');
$table->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$gitlabTest = new gitlabModelTest();

// 5. 强制要求：必须包含至少5个测试步骤
// 测试步骤1：使用有效参数创建标签（由于没有真实GitLab连接，期望返回null）
$validTag = new stdClass();
$validTag->tag_name = 'v1.0.0';
$validTag->ref = 'main';
$validTag->message = 'Release version 1.0.0';
$result = $gitlabTest->apiCreateTagTest(1, 1, $validTag);
if(empty($result)) $result = 'return empty';
r($result) && p() && e('return empty');

// 测试步骤2：使用空的tag_name参数
$emptyTagName = new stdClass();
$emptyTagName->tag_name = '';
$emptyTagName->ref = 'main';
r($gitlabTest->apiCreateTagTest(1, 1, $emptyTagName)) && p() && e('0');

// 测试步骤3：使用空的ref参数
$emptyRef = new stdClass();
$emptyRef->tag_name = 'v1.0.1';
$emptyRef->ref = '';
r($gitlabTest->apiCreateTagTest(1, 1, $emptyRef)) && p() && e('0');

// 测试步骤4：使用无效的gitlabID参数（getApiRoot返回空字符串）
$validTag2 = new stdClass();
$validTag2->tag_name = 'v1.0.2';
$validTag2->ref = 'main';
$result2 = $gitlabTest->apiCreateTagTest(999, 1, $validTag2);
if(empty($result2)) $result2 = 'return empty';
r($result2) && p() && e('return empty');

// 测试步骤5：使用无效的projectID参数（API调用会失败）
$validTag3 = new stdClass();
$validTag3->tag_name = 'v1.0.3';
$validTag3->ref = 'main';
$result3 = $gitlabTest->apiCreateTagTest(1, 999, $validTag3);
if(empty($result3)) $result3 = 'return empty';
r($result3) && p() && e('return empty');