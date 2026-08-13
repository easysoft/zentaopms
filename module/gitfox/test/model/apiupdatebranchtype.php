#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apiUpdateBranchType();
timeout=0
cid=0

- 步骤 1：apiUpdateBranchType 产生 dao 错误 @1
- 步骤 2：apiUpdateBranchType 返回 false @0
- 步骤 3：apiUpdateBranchType 返回值类型为 bool @bool
- 步骤 4：重复调用仍产生 dao 错误 @1
- 步骤 5：重复调用仍返回 false @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();

r($gitfoxTest->apiUpdateBranchTypeErrorTest(1, 1, array('name' => 'fix/*'))) && p() && e('1');
r($gitfoxTest->apiUpdateBranchTypeTest(1, 1, array('name' => 'fix/*'))) && p() && e('0');
r($gitfoxTest->apiUpdateBranchTypeTypeTest(1, 1, array('name' => 'fix/*'))) && p() && e('bool');
r($gitfoxTest->apiUpdateBranchTypeErrorTest(1, 1, array('name' => 'fix/*'))) && p() && e('1');
r($gitfoxTest->apiUpdateBranchTypeTest(1, 1, array('name' => 'fix/*'))) && p() && e('0');
