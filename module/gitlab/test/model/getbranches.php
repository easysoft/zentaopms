#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 gitlabModel::getBranches();
timeout=0
cid=16648

- 测试步骤1：正常获取有效GitLab项目的分支列表 @branch1
- 测试步骤2：获取空项目的分支列表，验证默认分支 @main
- 测试步骤3：测试无效的GitLab服务器ID，验证错误处理 @0
- 测试步骤4：测试无效的项目ID，验证错误处理 @0
- 测试步骤5：验证返回分支数组的结构和类型 @1

*/

zenData('pipeline')->gen(5);

$gitlab = new gitlabModelTest();

$projectIds = array(1, 2);
$branches   = $gitlab->getBranchesTest($gitlabID = 1, $projectIds[1]);

r($gitlab->getBranchesTest($gitlabID = 1, $projectIds[1]))  && p('0') && e('branch1');    // 测试步骤1：正常获取有效GitLab项目的分支列表
r($gitlab->getBranchesTest($gitlabID = 1, $projectIds[0]))  && p('0') && e('main');       // 测试步骤2：获取空项目的分支列表，验证默认分支
r($gitlab->getBranchesTest($gitlabID = 999, $projectIds[0])) && p('0') && e('0');         // 测试步骤3：测试无效的GitLab服务器ID，验证错误处理
r($gitlab->getBranchesTest($gitlabID = 1, 999))             && p('0') && e('0');          // 测试步骤4：测试无效的项目ID，验证错误处理
r(is_array($branches) && count($branches) > 0)             && p()    && e('1');          // 测试步骤5：验证返回分支数组的结构和类型