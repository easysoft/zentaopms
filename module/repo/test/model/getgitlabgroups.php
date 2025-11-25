#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getGitlabGroups();
timeout=0
cid=18060

- 步骤1：正常gitlabID查询群组第0条的text属性 @GitLab Instance
- 步骤2：验证群组数据结构第0条的value属性 @2
- 步骤3：无效gitlabID查询 @0
- 步骤4：边界值gitlabID查询 @0
- 步骤5：验证群组数量统计 @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

zenData('pipeline')->gen(5);

su('admin');

$repoTest = new repoTest();

r($repoTest->getGitlabGroupsTest(1))                  && p('0:text') && e('GitLab Instance');      // 步骤1：正常gitlabID查询群组
r($repoTest->getGitlabGroupsTest(1))                  && p('0:value') && e('2');                     // 步骤2：验证群组数据结构
r(count($repoTest->getGitlabGroupsTest(0)))            && p('') && e('0');                            // 步骤3：无效gitlabID查询
r(count($repoTest->getGitlabGroupsTest(-1)))          && p('') && e('0');                            // 步骤4：边界值gitlabID查询
r(count($repoTest->getGitlabGroupsTest(1)))           && p('') && e('3');                            // 步骤5：验证群组数量统计