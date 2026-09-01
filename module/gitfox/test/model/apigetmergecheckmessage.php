#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apigetmergecheckmessage();
timeout=0
cid=0

- 步骤 1：apiGetMergeCheckMessage 错误标记为 0 或 1 @1
- 步骤 2：apiGetMergeCheckMessage 返回 false @0
- 步骤 3：apiGetMergeCheckMessage 返回值类型为 bool @bool
- 步骤 4：重复调用错误标记仍为 0 或 1 @1
- 步骤 5：重复调用仍返回 false @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();

r(in_array($gitfoxTest->apiGetMergeCheckMessageErrorTest(1, 'feat', 'main'), array(0, 1))) && p() && e('1');
r($gitfoxTest->apiGetMergeCheckMessageTest(1, 'feat', 'main')) && p() && e('0');
r($gitfoxTest->apiGetMergeCheckMessageTypeTest(1, 'feat', 'main')) && p() && e('bool');
r(in_array($gitfoxTest->apiGetMergeCheckMessageErrorTest(1, 'feat', 'main'), array(0, 1))) && p() && e('1');
r($gitfoxTest->apiGetMergeCheckMessageTest(1, 'feat', 'main')) && p() && e('0');
