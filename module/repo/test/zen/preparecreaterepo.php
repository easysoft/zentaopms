#!/usr/bin/env php
<?php

/**

title=测试 repoZen::prepareCreateRepo();
timeout=0
cid=18151

- 执行result1 = $repoZenTest模块的prepareCreateRepoTest方法，参数是$normalRepo, 'normal' 属性acl @{"acl":"open"}
- 执行result2 = $repoZenTest模块的prepareCreateRepoTest方法，参数是$aclErrorRepo, 'acl_error'  @0
- 执行result3 = $repoZenTest模块的prepareCreateRepoTest方法，参数是null, 'normal'  @0
- 执行result4 = $repoZenTest模块的prepareCreateRepoTest方法，参数是$fullRepo, 'normal' 属性path @https://test.example.com/dev-group/full-test-repo
- 执行acl) && strpos($result1模块的acl, 'open') !== false方法  @1
- 执行acl) && json_decode($result4模块的acl) !== null方法  @1
- 执行result5 = $repoZenTest模块的prepareCreateRepoTest方法，参数是$noAclRepo, 'normal' 属性name @no-acl-repo

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

su('admin');

$repoZenTest = new repoZenTest();

// 准备测试数据 - 正常的代码库对象
$normalRepo = new stdclass();
$normalRepo->name = 'test-repo';
$normalRepo->acl = '{"acl":"open"}';
$normalRepo->serviceHost = '1';
$normalRepo->namespace = 'test-group';
$normalRepo->SCM = 'Gitlab';

// ACL错误场景的代码库对象
$aclErrorRepo = new stdclass();
$aclErrorRepo->name = 'test-repo-acl-error';
$aclErrorRepo->acl = '{"acl":"custom","groups":[],"users":[]}';
$aclErrorRepo->serviceHost = '1';
$aclErrorRepo->namespace = 'test-group';

// 包含必要字段的完整代码库对象
$fullRepo = new stdclass();
$fullRepo->name = 'full-test-repo';
$fullRepo->acl = '{"acl":"private"}';
$fullRepo->serviceHost = '2';
$fullRepo->namespace = 'dev-group';
$fullRepo->SCM = 'Gitlab';

// 无ACL字段的代码库对象
$noAclRepo = new stdclass();
$noAclRepo->name = 'no-acl-repo';
$noAclRepo->serviceHost = '1';
$noAclRepo->namespace = 'no-acl-group';

r($result1 = $repoZenTest->prepareCreateRepoTest($normalRepo, 'normal')) && p('acl') && e('{"acl":"open"}');
r($result2 = $repoZenTest->prepareCreateRepoTest($aclErrorRepo, 'acl_error')) && p() && e('0');
r($result3 = $repoZenTest->prepareCreateRepoTest(null, 'normal')) && p() && e('0');
r($result4 = $repoZenTest->prepareCreateRepoTest($fullRepo, 'normal')) && p('path') && e('https://test.example.com/dev-group/full-test-repo');
r(is_object($result1) && isset($result1->acl) && strpos($result1->acl, 'open') !== false) && p() && e('1');
r(is_object($result4) && isset($result4->acl) && json_decode($result4->acl) !== null) && p() && e('1');
r($result5 = $repoZenTest->prepareCreateRepoTest($noAclRepo, 'normal')) && p('name') && e('no-acl-repo');