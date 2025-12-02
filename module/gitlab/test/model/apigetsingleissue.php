#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::apiGetSingleIssue();
cid=16617

- 测试步骤1：正常获取存在的issue信息 >> 返回有效issue对象的title属性
- 测试步骤2：使用不存在的gitlabID查询issue >> 返回空结果或空字符串
- 测试步骤3：使用不存在的projectID查询issue >> 返回404项目未找到错误信息
- 测试步骤4：使用不存在的issueID查询issue >> 返回404 issue未找到错误信息
- 测试步骤5：使用负数issueID查询issue >> 正确处理无效ID参数
- 测试步骤6：使用超大gitlabID查询issue >> 验证大数值参数的处理
- 测试步骤7：使用超大projectID查询issue >> 验证大数值项目ID的处理

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';

// 1. zendata数据准备
$table = zenData('pipeline');
$table->type->range('gitlab');
$table->name->range('GitLab-Instance-{1,2,3,4,5}');
$table->url->range('https://gitlab.example.com');
$table->token->range('glpat-xxxxxxxxxxxxxxxxxxxx');
$table->gen(5);

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$gitlab = new gitlabTest();

// 4. 执行测试步骤 - 符合指南要求的至少5个测试步骤
r($gitlab->apiGetSingleIssueTest(1, 2, 1)) && p('title') && e('issue1');                      // 步骤1：正常获取存在的issue信息
r($gitlab->apiGetSingleIssueTest(0, 2, 1)) && p() && e('0');                                  // 步骤2：使用不存在的gitlabID查询issue
r($gitlab->apiGetSingleIssueTest(1, 0, 1)) && p('message') && e('404 Project Not Found');    // 步骤3：使用不存在的projectID查询issue
r($gitlab->apiGetSingleIssueTest(1, 2, 10001)) && p('message') && e('404 Not found');        // 步骤4：使用不存在的issueID查询issue
r($gitlab->apiGetSingleIssueTest(1, 1, -1)) && p('message') && e('404 Not found');           // 步骤5：使用负数issueID查询issue
r($gitlab->apiGetSingleIssueTest(999, 2, 1)) && p() && e('0');                               // 步骤6：使用超大gitlabID查询issue
r($gitlab->apiGetSingleIssueTest(1, 999999, 1)) && p('message') && e('404 Project Not Found'); // 步骤7：使用超大projectID查询issue