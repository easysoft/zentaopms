#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';
su('admin');

/**

title=测试 gitlabModel::apiGetSingleBranch();
timeout=0
cid=16615

- 查询正确的branch信息属性name @branch1
- 使用不存在的gitlabID查询branch信息 @0
- 使用不存在的projectID查询branch信息属性message @404 Project Not Found
- 使用不存在的branch名称查询branch信息属性message @404 Branch Not Found
- 使用空字符串作为分支名称查询branch信息属性message @404 Branch Not Found
- 使用特殊字符作为分支名称查询branch信息属性message @404 Branch Not Found
- 使用极长分支名称查询branch信息属性message @404 Branch Not Found

*/

// 准备测试数据
zendata('pipeline')->loadYaml('pipeline_apigetsinglebranch', false, 2)->gen(10);

$gitlab = new gitlabTest();

// 执行测试
$branch1 = $gitlab->apiGetSingleBranchTest(1, 2, 'branch1');
$branch2 = $gitlab->apiGetSingleBranchTest(0, 2, 'branch1');
$branch3 = $gitlab->apiGetSingleBranchTest(1, 0, 'branch1');
$branch4 = $gitlab->apiGetSingleBranchTest(1, 2, 'branch12345');
$branch5 = $gitlab->apiGetSingleBranchTest(1, 2, '');
$branch6 = $gitlab->apiGetSingleBranchTest(1, 2, 'feature/test@#$%');
$branch7 = $gitlab->apiGetSingleBranchTest(1, 2, str_repeat('a', 300));

r($branch1) && p('name')    && e('branch1');               // 查询正确的branch信息
r($branch2) && p()          && e('0');                     // 使用不存在的gitlabID查询branch信息
r($branch3) && p('message') && e('404 Project Not Found'); // 使用不存在的projectID查询branch信息
r($branch4) && p('message') && e('404 Branch Not Found');  // 使用不存在的branch名称查询branch信息
r($branch5) && p('message') && e('404 Branch Not Found');  // 使用空字符串作为分支名称查询branch信息
r($branch6) && p('message') && e('404 Branch Not Found');  // 使用特殊字符作为分支名称查询branch信息
r($branch7) && p('message') && e('404 Branch Not Found');  // 使用极长分支名称查询branch信息