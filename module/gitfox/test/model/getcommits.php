#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::getCommits();
timeout=0
cid=0

- 步骤 1：getCommits 返回值类型为 array @array
- 步骤 2：getCommits 默认返回 0 条提交 @0
- 步骤 3：getCommits 不产生 dao 错误 @0
- 步骤 4：传入完整参数时返回值类型仍为 array @array
- 步骤 5：重复调用 getCommits 返回值类型仍为 array @array

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$gitfoxTest = new gitfoxModelTest();

$repo = new stdclass();
$repo->id       = 1;
$repo->scmType  = 'git';
$repo->client   = 'http://gitfox.test';
$repo->apiPath  = 'http://gitfox.test/api/v1/';
$repo->password = 'token';

r($gitfoxTest->getCommitsTypeTest($repo, '')) && p() && e('array');
r($gitfoxTest->getCommitsCountTest($repo, '')) && p() && e('0');
r($gitfoxTest->getCommitsErrorTest($repo, '')) && p() && e('0');
r($gitfoxTest->getCommitsTypeTest($repo, '', null, '', '', null)) && p() && e('array');
r($gitfoxTest->getCommitsTypeTest($repo, '')) && p() && e('array');
