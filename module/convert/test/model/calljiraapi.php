#!/usr/bin/env php
<?php

/**

title=测试 convertModel::callJiraAPI();
timeout=0
cid=15761

- 步骤1：空Session情况 @0
- 步骤2：无效的jiraApi Session数据 @0
- 步骤3：缺少domain的jiraApi配置 @0
- 步骤4：正常的jiraApi配置但模拟HTTP请求失败 @0
- 步骤5：正常的jiraApi配置和有效URL参数 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($convertTest->callJiraAPITest('/test/api', 0)) && p() && e('0'); // 步骤1：空Session情况
r($convertTest->callJiraAPITest('/rest/api/2/status', 10)) && p() && e('0'); // 步骤2：无效的jiraApi Session数据
r($convertTest->callJiraAPITest('/rest/api/2/project', 0)) && p() && e('0'); // 步骤3：缺少domain的jiraApi配置
r($convertTest->callJiraAPITest('', 5)) && p() && e('0'); // 步骤4：正常的jiraApi配置但模拟HTTP请求失败
r($convertTest->callJiraAPITest('/rest/api/2/issue/search?jql=project=TEST', 20)) && p() && e('0'); // 步骤5：正常的jiraApi配置和有效URL参数