#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apigetgroups();
timeout=0
cid=0

- 步骤 1：apiGetGroups 不产生 dao 错误 @0
- 步骤 2：apiGetGroups 返回值类型为 array @array
- 步骤 3：apiGetGroups 默认结果为空 @0
- 步骤 4：按关键字过滤后结果仍为空 @0
- 步骤 5：重复调用结果仍为空 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();

r($gitfoxTest->apiGetGroupsErrorTest(1)) && p() && e('0');
r($gitfoxTest->apiGetGroupsTypeTest(1)) && p() && e('array');
r($gitfoxTest->apiGetGroupsCountTest(1)) && p() && e('0');
r($gitfoxTest->apiGetGroupsCountTest(1, 'id_desc', false, 'missing-group')) && p() && e('0');
r($gitfoxTest->apiGetGroupsCountTest(1, 'id_desc')) && p() && e('0');
