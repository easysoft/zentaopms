#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::apiDeleteLabel();
timeout=0
cid=16590

- 执行gitlabTest模块的apiDeleteLabelTest方法，参数是$gitlabID, 0, $validLabelName  @0
- 执行gitlabTest模块的apiDeleteLabelTest方法，参数是$gitlabID, $projectID, ''  @0
- 执行gitlabTest模块的apiDeleteLabelTest方法，参数是0, $projectID, $validLabelName  @0
- 执行gitlabTest模块的apiDeleteLabelTest方法，参数是$gitlabID, $projectID, $nonExistentLabelName  @0
- 执行gitlabTest模块的apiDeleteLabelTest方法，参数是$gitlabID, $projectID, $validLabelName  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';

zenData('pipeline')->gen(5);

su('admin');

$gitlabTest = new gitlabTest();

$gitlabID  = 1;
$projectID = 2;
$validLabelName = 'unitLabelTest';
$nonExistentLabelName = 'nonExistentLabel';

r($gitlabTest->apiDeleteLabelTest($gitlabID, 0, $validLabelName)) && p() && e('0');
r($gitlabTest->apiDeleteLabelTest($gitlabID, $projectID, '')) && p() && e('0');
r($gitlabTest->apiDeleteLabelTest(0, $projectID, $validLabelName)) && p() && e('0');
r($gitlabTest->apiDeleteLabelTest($gitlabID, $projectID, $nonExistentLabelName)) && p() && e('0');
r($gitlabTest->apiDeleteLabelTest($gitlabID, $projectID, $validLabelName)) && p() && e('0');