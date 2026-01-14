#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::apiDeleteGroup();
timeout=0
cid=16586

- 执行gitlabTest模块的apiDeleteGroupTest方法，参数是$validGitlabID, $emptyGroupID  @0
- 执行gitlabTest模块的apiDeleteGroupTest方法，参数是$invalidGitlabID, $validGroupID  @null
- 执行gitlabTest模块的apiDeleteGroupTest方法，参数是$validGitlabID, $negativeGroupID  @null
- 执行gitlabTest模块的apiDeleteGroupTest方法，参数是$validGitlabID, 999999  @null
- 执行gitlabTest模块的apiDeleteGroupTest方法，参数是$validGitlabID, $validGroupID  @null

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$gitlabTest = new gitlabModelTest();

$validGitlabID = 1;
$validGroupID = 12345;
$invalidGitlabID = 0;
$emptyGroupID = 0;
$negativeGroupID = -1;

r($gitlabTest->apiDeleteGroupTest($validGitlabID, $emptyGroupID)) && p() && e('0');
r($gitlabTest->apiDeleteGroupTest($invalidGitlabID, $validGroupID)) && p() && e('null');
r($gitlabTest->apiDeleteGroupTest($validGitlabID, $negativeGroupID)) && p() && e('null');
r($gitlabTest->apiDeleteGroupTest($validGitlabID, 999999)) && p() && e('null');
r($gitlabTest->apiDeleteGroupTest($validGitlabID, $validGroupID)) && p() && e('null');