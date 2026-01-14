#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::saveAuthInfo();
timeout=0
cid=16814

- 步骤1：非devops应用 @notDevopsApp
- 步骤2：已存在pipeline记录 @pipelineExists
- 步骤3：gitlab应用 @noSettingsMapping
- 步骤4：jenkins应用 @noSettingsMapping
- 步骤5：sonarqube应用 @noSettingsMapping

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 不需要zendata数据准备，使用纯逻辑测试

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$instanceTest = new instanceModelTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($instanceTest->saveAuthInfoTest((object)array('id' => 1, 'chart' => 'zentao'))) && p() && e('notDevopsApp'); // 步骤1：非devops应用
r($instanceTest->saveAuthInfoTest((object)array('id' => 2, 'chart' => 'gitlab'))) && p() && e('pipelineExists'); // 步骤2：已存在pipeline记录
r($instanceTest->saveAuthInfoTest((object)array('id' => 3, 'chart' => 'gitlab', 'name' => 'test-gitlab', 'domain' => 'test3.domain.com', 'appVersion' => '1.0'))) && p() && e('noSettingsMapping'); // 步骤3：gitlab应用
r($instanceTest->saveAuthInfoTest((object)array('id' => 4, 'chart' => 'jenkins', 'name' => 'test-jenkins', 'domain' => 'test4.domain.com', 'appVersion' => '2.0'))) && p() && e('noSettingsMapping'); // 步骤4：jenkins应用
r($instanceTest->saveAuthInfoTest((object)array('id' => 5, 'chart' => 'sonarqube', 'name' => 'test-sonar', 'domain' => 'test5.domain.com', 'appVersion' => '3.0'))) && p() && e('noSettingsMapping'); // 步骤5：sonarqube应用