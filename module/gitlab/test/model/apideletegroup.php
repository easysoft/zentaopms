#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::apiDeleteGroup();
timeout=0
cid=0

- 执行gitlabTest模块的apiDeleteGroupTest方法，参数是$validGitlabID, $emptyGroupID  @0
- 执行gitlabTest模块的apiDeleteGroupTest方法，参数是$invalidGitlabID, $validGroupID  @~~
- 执行gitlabTest模块的apiDeleteGroupTest方法，参数是$validGitlabID, $negativeGroupID  @0
- 执行gitlabTest模块的apiDeleteGroupTest方法，参数是$validGitlabID, 999999  @~~
- 执行gitlabTest模块的apiDeleteGroupTest方法，参数是$validGitlabID, $validGroupID  @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';

zenData('pipeline')->gen(5);

su('admin');

$gitlabTest = new gitlabTest();

$validGitlabID = 1;
$validGroupID = 12345;
$invalidGitlabID = 0;
$emptyGroupID = 0;
$negativeGroupID = -1;

r($gitlabTest->apiDeleteGroupTest($validGitlabID, $emptyGroupID)) && p() && e('0');
r($gitlabTest->apiDeleteGroupTest($invalidGitlabID, $validGroupID)) && p() && e('~~');
r($gitlabTest->apiDeleteGroupTest($validGitlabID, $negativeGroupID)) && p() && e('0');
r($gitlabTest->apiDeleteGroupTest($validGitlabID, 999999)) && p() && e('~~');
r($gitlabTest->apiDeleteGroupTest($validGitlabID, $validGroupID)) && p() && e('~~');