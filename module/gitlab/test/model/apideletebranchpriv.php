#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::apiDeleteBranchPriv();
timeout=0
cid=16585

- 步骤1：gitlabID为0 @0
- 步骤2：项目不存在属性message @404 Project Not Found
- 步骤3：分支不存在属性message @404 Not found
- 步骤4：特殊字符分支名属性message @404 Not found
- 步骤5：正常删除权限 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';

su('admin');

$gitlabTest = new gitlabTest();

r($gitlabTest->apiDeleteBranchPrivTest(0, 1, 'master')) && p() && e('0'); // 步骤1：gitlabID为0
r($gitlabTest->apiDeleteBranchPrivTest(1, 999, 'master')) && p('message') && e('404 Project Not Found'); // 步骤2：项目不存在
r($gitlabTest->apiDeleteBranchPrivTest(1, 2, 'nonexistent')) && p('message') && e('404 Not found'); // 步骤3：分支不存在
r($gitlabTest->apiDeleteBranchPrivTest(1, 2, 'feature/test-branch')) && p('message') && e('404 Not found'); // 步骤4：特殊字符分支名
r($gitlabTest->apiDeleteBranchPrivTest(1, 2, 'master')) && p() && e('0'); // 步骤5：正常删除权限