#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::apiGetJobs();
timeout=0
cid=16607

- 步骤1：正常查询pipeline jobs第0条的stage属性 @deploy
- 步骤2：不存在的GitLab ID @0
- 步骤3：不存在的项目ID属性message @404 Project Not Found
- 步骤4：不存在的流水线ID属性message @404 Not found
- 步骤5：GitLab ID为0 @0
- 步骤6：负数项目ID属性message @404 Project Not Found
- 步骤7：流水线ID为0属性message @404 Not found

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('pipeline')->gen(5);

su('admin');

$gitlabTest = new gitlabModelTest();

r($gitlabTest->apiGetJobsTest(1, 2, 8)) && p('0:stage') && e('deploy');                   // 步骤1：正常查询pipeline jobs
r($gitlabTest->apiGetJobsTest(999, 2, 8)) && p() && e('0');                               // 步骤2：不存在的GitLab ID
r($gitlabTest->apiGetJobsTest(1, 999999, 8)) && p('message') && e('404 Project Not Found'); // 步骤3：不存在的项目ID
r($gitlabTest->apiGetJobsTest(1, 2, 999999)) && p('message') && e('404 Not found');       // 步骤4：不存在的流水线ID
r($gitlabTest->apiGetJobsTest(0, 2, 8)) && p() && e('0');                                 // 步骤5：GitLab ID为0
r($gitlabTest->apiGetJobsTest(1, -1, 8)) && p('message') && e('404 Project Not Found');  // 步骤6：负数项目ID
r($gitlabTest->apiGetJobsTest(1, 2, 0)) && p('message') && e('404 Not found');           // 步骤7：流水线ID为0