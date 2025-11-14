#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::apiUpdateProjectMember();
timeout=0
cid=16632

- 执行gitlabTest模块的apiUpdateProjectMemberTest方法，参数是$gitlabID, $projectID, $projectMember  @return false
- 执行gitlabTest模块的apiUpdateProjectMemberTest方法，参数是$gitlabID, $projectID, $projectMember  @return false
- 执行gitlabTest模块的apiUpdateProjectMemberTest方法，参数是999, $projectID, $projectMember  @0
- 执行gitlabTest模块的apiUpdateProjectMemberTest方法，参数是$gitlabID, 0, $projectMember  @0
- 执行gitlabTest模块的apiUpdateProjectMemberTest方法，参数是$gitlabID, $projectID, $projectMember  @0
- 执行gitlabTest模块的apiUpdateProjectMemberTest方法，参数是$gitlabID, $projectID, $projectMember 属性access_level @30
- 执行gitlabTest模块的apiUpdateProjectMemberTest方法，参数是$gitlabID, $projectID, $projectMember 属性access_level @40

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';

// 2. zendata数据准备
$table = zenData('pipeline');
$table->id->range('1-5');
$table->name->range('test1,test2,test3,test4,test5');
$table->type->range('gitlab');
$table->url->range('http://gitlab.test{1-5}.com');
$table->token->range('token{1-5}');
$table->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$gitlabTest = new gitlabTest();

// 5. 测试步骤（必须至少5个）
$gitlabID  = 1;
$projectID = 4;

// 测试步骤1：测试空的user_id参数
$projectMember = new stdclass();
$projectMember->user_id      = '';
$projectMember->access_level = '40';
r($gitlabTest->apiUpdateProjectMemberTest($gitlabID, $projectID, $projectMember)) && p() && e('return false');

// 测试步骤2：测试空的access_level参数
$projectMember->user_id      = '4';
$projectMember->access_level = '';
r($gitlabTest->apiUpdateProjectMemberTest($gitlabID, $projectID, $projectMember)) && p() && e('return false');

// 测试步骤3：测试无效的gitlabID
$projectMember->access_level = '40';
r($gitlabTest->apiUpdateProjectMemberTest(999, $projectID, $projectMember)) && p() && e('0');

// 测试步骤4：测试无效的projectID
r($gitlabTest->apiUpdateProjectMemberTest($gitlabID, 0, $projectMember)) && p() && e('0');

// 测试步骤5：测试无效的memberID
$projectMember->user_id = '999999';
r($gitlabTest->apiUpdateProjectMemberTest($gitlabID, $projectID, $projectMember)) && p() && e('0');

// 测试步骤6：测试正常更新成员权限到开发者级别
$projectMember->user_id      = '4';
$projectMember->access_level = '30';
r($gitlabTest->apiUpdateProjectMemberTest($gitlabID, $projectID, $projectMember)) && p('access_level') && e('30');

// 测试步骤7：测试正常更新成员权限到维护者级别
$projectMember->access_level = '40';
r($gitlabTest->apiUpdateProjectMemberTest($gitlabID, $projectID, $projectMember)) && p('access_level') && e('40');