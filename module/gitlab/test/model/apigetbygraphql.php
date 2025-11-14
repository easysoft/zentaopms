#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';

/**

title=测试 gitlabModel::apiGetByGraphql();
timeout=0
cid=16600

- 测试步骤1：空查询字符串 @Unexpected end of document
- 测试步骤2：无效的GraphQL语法 @Parse error on "error" (IDENTIFIER) at [1, 1]

- 测试步骤3：正确的GraphQL查询第lastCommit条的sha属性 @1b9405639ddef9585b3743b0637b4f79775409b7
- 测试步骤4：复杂的GraphQL查询第lastCommit条的sha属性 @1b9405639ddef9585b3743b0637b4f79775409b7
- 测试步骤5：查询不存在的项目路径 @0

*/

zenData('pipeline')->loadYaml('pipeline')->gen(5);
zenData('repo')->loadYaml('repo')->gen(1);

su('admin');

$gitlab = new gitlabTest();

r($gitlab->apiGetByGraphqlTest('')) && p('0') && e('Unexpected end of document'); // 测试步骤1：空查询字符串
r($gitlab->apiGetByGraphqlTest('error')) && p('0') && e('Parse error on "error" (IDENTIFIER) at [1, 1]'); // 测试步骤2：无效的GraphQL语法

$validQuery = 'query {project(fullPath: "gitlab-instance-76af86df/testhtml") {repository {tree(path: "") {lastCommit {sha message}}}}}';
r($gitlab->apiGetByGraphqlTest($validQuery)) && p('lastCommit:sha') && e('1b9405639ddef9585b3743b0637b4f79775409b7'); // 测试步骤3：正确的GraphQL查询

$complexQuery = 'query {project(fullPath: "gitlab-instance-76af86df/testhtml") {repository {tree(path: "") {lastCommit {sha message}}}}}';
r($gitlab->apiGetByGraphqlTest($complexQuery)) && p('lastCommit:sha') && e('1b9405639ddef9585b3743b0637b4f79775409b7'); // 测试步骤4：复杂的GraphQL查询

$invalidPathQuery = 'query {project(fullPath: "nonexistent/project") {repository {tree(path: "") {lastCommit {sha message}}}}}';
r($gitlab->apiGetByGraphqlTest($invalidPathQuery)) && p() && e(0); // 测试步骤5：查询不存在的项目路径