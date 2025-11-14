#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::getProjectName();
timeout=0
cid=16655

- 测试步骤1：获取有效GitLab服务器1项目ID 1的项目名称 @Monitoring
- 测试步骤2：获取有效GitLab服务器1项目ID 2的项目名称 @testHtml
- 测试步骤3：获取不存在的GitLab服务器ID 10的项目名称 @0
- 测试步骤4：使用无效的项目ID 0获取项目名称 @0
- 测试步骤5：使用负数项目ID -1获取项目名称 @0
- 测试步骤6：使用非常大的项目ID获取项目名称 @0
- 测试步骤7：使用不存在的GitLab服务器ID验证错误处理 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';

zenData('pipeline')->gen(10);

su('admin');

$gitlab = new gitlabTest();

r($gitlab->getProjectNameTest(1, 1)) && p() && e('Monitoring'); // 测试步骤1：获取有效GitLab服务器1项目ID 1的项目名称
r($gitlab->getProjectNameTest(1, 2)) && p() && e('testHtml'); // 测试步骤2：获取有效GitLab服务器1项目ID 2的项目名称
r($gitlab->getProjectNameTest(10, 1)) && p() && e('0'); // 测试步骤3：获取不存在的GitLab服务器ID 10的项目名称
r($gitlab->getProjectNameTest(1, 0)) && p() && e('0'); // 测试步骤4：使用无效的项目ID 0获取项目名称
r($gitlab->getProjectNameTest(1, -1)) && p() && e('0'); // 测试步骤5：使用负数项目ID -1获取项目名称
r($gitlab->getProjectNameTest(1, 99999)) && p() && e('0'); // 测试步骤6：使用非常大的项目ID获取项目名称
r($gitlab->getProjectNameTest(999, 1)) && p() && e('0'); // 测试步骤7：使用不存在的GitLab服务器ID验证错误处理