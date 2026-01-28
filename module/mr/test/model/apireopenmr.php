#!/usr/bin/env php
<?php

/**

title=测试 mrModel::apiReopenMR();
timeout=0
cid=17233

- 测试步骤1：正常的gitlab主机重新打开MR @0
- 测试步骤2：不存在的主机ID重新打开MR @0
- 测试步骤3：非gitlab类型主机(jenkins)重新打开MR @0
- 测试步骤4：另一个jenkins类型主机重新打开MR @0
- 测试步骤5：gitea类型主机重新打开MR @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$pipeline = zenData('pipeline');
$pipeline->id->range('1-5');
$pipeline->type->range('gitlab{2},jenkins{2},gitea{1}');
$pipeline->name->range('GitLab测试服务器,GitLab生产服务器,Jenkins CI,Jenkins Build,Gitea服务器');
$pipeline->url->range('https://gitlab.test.com,https://gitlab.prod.com,https://jenkins.test.com,https://jenkins.prod.com,https://gitea.test.com');
$pipeline->account->range('testuser{2},jenkinsuser{2},giteauser{1}');
$pipeline->token->range('gitlab_token_123,gitlab_token_456,jenkins_token_789,jenkins_token_abc,gitea_token_def');
$pipeline->deleted->range('0{5}');
$pipeline->gen(5);

su('admin');

$mrTest = new mrModelTest();

r($mrTest->apiReopenMRTest(1, '3', 138)) && p() && e('0'); // 测试步骤1：正常的gitlab主机重新打开MR
r($mrTest->apiReopenMRTest(999, '3', 138)) && p() && e('0'); // 测试步骤2：不存在的主机ID重新打开MR
r($mrTest->apiReopenMRTest(3, '3', 138)) && p() && e('0'); // 测试步骤3：非gitlab类型主机(jenkins)重新打开MR
r($mrTest->apiReopenMRTest(4, '3', 138)) && p() && e('0'); // 测试步骤4：另一个jenkins类型主机重新打开MR
r($mrTest->apiReopenMRTest(5, '3', 138)) && p() && e('0'); // 测试步骤5：gitea类型主机重新打开MR