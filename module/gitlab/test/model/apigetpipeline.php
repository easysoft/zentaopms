#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 gitlabModel::apiGetPipeline();
timeout=0
cid=16611

- 步骤1：正常参数查询pipeline @0
- 步骤2：使用无效gitlabID查询 @0
- 步骤3：使用无效projectID查询 @0
- 步骤4：使用不存在的分支查询 @0
- 步骤5：边界值测试使用0作为参数 @0
- 步骤6：特殊字符分支名称查询 @0

*/

zenData('pipeline')->gen(5);

$gitlabTest = new gitlabModelTest();

r($gitlabTest->apiGetPipelineTest(1, 2, 'master')) && p() && e('0'); // 步骤1：正常参数查询pipeline
r($gitlabTest->apiGetPipelineTest(999, 2, 'master')) && p() && e('0'); // 步骤2：使用无效gitlabID查询
r($gitlabTest->apiGetPipelineTest(1, 999, 'master')) && p() && e('0'); // 步骤3：使用无效projectID查询
r($gitlabTest->apiGetPipelineTest(1, 2, 'nonexistent')) && p() && e('0'); // 步骤4：使用不存在的分支查询
r($gitlabTest->apiGetPipelineTest(0, 0, '')) && p() && e('0'); // 步骤5：边界值测试使用0作为参数
r($gitlabTest->apiGetPipelineTest(1, 2, 'feature/test-branch')) && p() && e('0'); // 步骤6：特殊字符分支名称查询