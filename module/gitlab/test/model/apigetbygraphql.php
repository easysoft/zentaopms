#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试 gitlabModel::apiGetByGraphql();
timeout=0
cid=1

- 空的查询参数 @Unexpected end of document
- 错误的查询参数 @Parse error on "error" (IDENTIFIER) at [1, 1]

- 正确的参数第lastCommit条的sha属性 @1b9405639ddef9585b3743b0637b4f79775409b7

*/

zdTable('pipeline')->gen(5);
zdTable('repo')->config('repo')->gen(1);

$gitlab = new gitlabTest();

r($gitlab->apiGetByGraphqlTest(''))   && p('0') && e('Unexpected end of document'); // 空的查询参数
r($gitlab->apiGetByGraphqlTest('error')) && p('0') && e('Parse error on "error" (IDENTIFIER) at [1, 1]'); // 错误的查询参数

$real = 'query {project(fullPath: "gitlab-instance-76af86df/testhtml") {repository {tree(path: "") {lastCommit {sha message}}}}}';
r($gitlab->apiGetByGraphqlTest($real)) && p('lastCommit:sha') && e('1b9405639ddef9585b3743b0637b4f79775409b7'); // 正确的参数