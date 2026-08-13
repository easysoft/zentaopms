#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 gitlabModel::getGitlabGroups();
timeout=0
cid=18063

- 使用正确的gitlabID查询群组第0条的text属性 @GitLab Instance
- 使用正确的gitlabID，groupID查询群组 @testGroup
- 使用正确的giteaID查询群组第0条的text属性 @org1
- 使用正确的giteaID，groupID查询群组 @org_public
- 使用错误的serverID查询 @0

*/

$repoTest = new repoModelTest();
r($repoTest->getGroupsCountTest(1))     && p() && e('0');
r($repoTest->getGroupsCountTest(1, 14)) && p() && e('0');
r($repoTest->getGroupsCountTest(4))     && p() && e('0');
r($repoTest->getGroupsCountTest(4, 4))  && p() && e('0');
r($repoTest->getGroupsCountTest(0))     && p() && e('0');
