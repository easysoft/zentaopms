#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::getGitlabGroups();
timeout=0
cid=1

- 使用正确的gitlabID查询群组第0条的text属性 @GitLab Instance
- 使用正确的gitlabID，groupID查询群组 @testGroup
- 使用正确的giteaID查询群组第0条的text属性 @org1
- 使用正确的giteaID，groupID查询群组 @org_public
- 使用错误的serverID查询 @0

*/

zdTable('pipeline')->gen(5);

$repo = $tester->loadModel('repo');

$gitlabID      = 1;
$gitlabGroupID = 14;
$giteaID       = 4;
$giteaGroupID  = 4;

r($repo->getGroups($gitlabID))                 && p('0:text') && e('GitLab Instance'); //使用正确的gitlabID查询群组
r($repo->getGroups($gitlabID, $gitlabGroupID)) && p()         && e('testGroup');       //使用正确的gitlabID，groupID查询群组
r($repo->getGroups($giteaID))                  && p('0:text') && e('org1');            //使用正确的giteaID查询群组
r($repo->getGroups($giteaID, $giteaGroupID))   && p()         && e('org_public');      //使用正确的giteaID，groupID查询群组
r($repo->getGroups(0))                         && p()         && e('0');               //使用错误的serverID查询