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
- 测试 parseRepoAcl 解析 members 中的用户 @dev1
- 测试 parseRepoAcl 解析 members 中的分组映射账号 @qa1
- 测试 parseRepoAcl 传入非法 JSON 返回默认 open @open
- 测试 parseRepoAcl 传入空 acl 返回默认 open @open
*/

$repo = new repoModelTest();

$result = $repo->parseRepoAclTest();
r($result) && p('acl') && e('private');
r($result['members']) && p('0') && e('dev1');

$result = $repo->parseRepoAclTest('{"acl":"private","users":["dev1"," dev2 ",""],"groups":["1"]}', array(1 => array('qa1', ' qa2 ', '')));
r($result['members']) && p('2') && e('qa1');

$result = $repo->parseRepoAclTest('{bad json}', array(1 => array('qa1', ' qa2 ', '')));
r($result) && p('acl') && e('open');

$result = $repo->parseRepoAclTest('', array(1 => array('qa1', ' qa2 ', '')));
r($result) && p('acl') && e('open');
