#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 repoModel->parseRepoAcl();
timeout=0
cid=18122

- 测试 parseRepoAcl 解析 acl 字段 @private
- 测试 parseRepoAcl 解析 users 列表 @dev1,dev2
- 测试 parseRepoAcl 解析 groupAccounts 列表 @qa1,qa2
- 测试 parseRepoAcl 传入非法 JSON 返回默认 open @open
- 测试 parseRepoAcl 传入空 acl 返回默认 open @open
*/

$repo = new repoModelTest();

$result = $repo->parseRepoAclTest();
r($result) && p('acl') && e('private');
r($result) && p('users') && e('dev1,dev2');

$result = $repo->parseRepoAclTest('{"acl":"private","users":["dev1"," dev2 ",""]}', ' qa1, qa2, ');
r($result) && p('groupAccounts') && e('qa1,qa2');

$result = $repo->parseRepoAclTest('{bad json}', ' qa1, qa2, ');
r($result) && p('acl') && e('open');

$result = $repo->parseRepoAclTest('', ' qa1, qa2, ');
r($result) && p('acl') && e('open');
