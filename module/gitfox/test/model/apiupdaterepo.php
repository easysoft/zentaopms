#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apiUpdateRepo();
timeout=0
cid=0

- 步骤 1：apiUpdateRepo 产生 dao 错误 @1
- 步骤 2：apiUpdateRepo 返回 false @0
- 步骤 3：apiUpdateRepo 返回值类型为 bool @bool
- 步骤 4：重复调用仍产生 dao 错误 @1
- 步骤 5：重复调用仍返回 false @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$repo       = (object)array('name' => 'test', 'defaultBranch' => 'main', 'desc' => 'test', 'acl' => 'private', 'space' => 1, 'product' => 1);

r($gitfoxTest->apiUpdateRepoErrorTest(1, $repo)) && p() && e('1');
r($gitfoxTest->apiUpdateRepoTest(1, $repo)) && p() && e('0');
r($gitfoxTest->apiUpdateRepoTypeTest(1, $repo)) && p() && e('bool');
r($gitfoxTest->apiUpdateRepoErrorTest(1, $repo)) && p() && e('1');
r($gitfoxTest->apiUpdateRepoTest(1, $repo)) && p() && e('0');
