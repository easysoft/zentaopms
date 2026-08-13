#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apiUpdateSpace();
timeout=0
cid=0

- 步骤 1：apiUpdateSpace 产生 dao 错误 @1
- 步骤 2：apiUpdateSpace 返回 false @0
- 步骤 3：apiUpdateSpace 返回值类型为 bool @bool
- 步骤 4：重复调用仍产生 dao 错误 @1
- 步骤 5：重复调用仍返回 false @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();

r($gitfoxTest->apiUpdateSpaceErrorTest(1, (object)array('name' => 'updated'))) && p() && e('1');
r($gitfoxTest->apiUpdateSpaceTest(1, (object)array('name' => 'updated'))) && p() && e('0');
r($gitfoxTest->apiUpdateSpaceTypeTest(1, (object)array('name' => 'updated'))) && p() && e('bool');
r($gitfoxTest->apiUpdateSpaceErrorTest(1, (object)array('name' => 'updated'))) && p() && e('1');
r($gitfoxTest->apiUpdateSpaceTest(1, (object)array('name' => 'updated'))) && p() && e('0');
