#!/usr/bin/env php
<?php

/**

title=测试 gogsModel::apiGetMergeRequests();
timeout=0
cid=16685

- 步骤1：正常的gogsID和项目名称 @0
- 步骤2：无效的gogsID（0） @0
- 步骤3：空的项目名称 @0
- 步骤4：不存在的gogsID（999） @0
- 步骤5：特殊字符的项目名称 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('pipeline')->loadYaml('pipeline', false, 2)->gen(7);
zenData('oauth')->loadYaml('oauth')->gen(1);

su('admin');

$gogsTest = new gogsModelTest();

r($gogsTest->apiGetMergeRequestsTest(5, 'testuser/testrepo')) && p() && e('0'); // 步骤1：正常的gogsID和项目名称
r($gogsTest->apiGetMergeRequestsTest(0, 'testuser/testrepo')) && p() && e('0'); // 步骤2：无效的gogsID（0）
r($gogsTest->apiGetMergeRequestsTest(5, '')) && p() && e('0'); // 步骤3：空的项目名称
r($gogsTest->apiGetMergeRequestsTest(999, 'testuser/testrepo')) && p() && e('0'); // 步骤4：不存在的gogsID（999）
r($gogsTest->apiGetMergeRequestsTest(5, 'test@user/test-repo_1')) && p() && e('0'); // 步骤5：特殊字符的项目名称