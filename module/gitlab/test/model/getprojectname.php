#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试gitlabModel->getProjectName();
timeout=0
cid=1

- 获取gitlab服务器1项目id 1的项目名称。 @Monitoring
- 获取gitlab服务器1项目id 2的项目名称。 @testHtml
- 获取不存在gitlab服务器项目id 1的项目名称。 @0

*/

zdTable('pipeline')->gen(5);

$gitlab = new gitlabTest();

$projectIds = array(1, 2);

r($gitlab->getProjectNameTest($gitlabID = 1, $projectIds[0]))  && p() && e('Monitoring'); // 获取gitlab服务器1项目id 1的项目名称。
r($gitlab->getProjectNameTest($gitlabID = 1, $projectIds[1]))  && p() && e('testHtml'); // 获取gitlab服务器1项目id 2的项目名称。
r($gitlab->getProjectNameTest($gitlabID = 10, $projectIds[0])) && p() && e('0'); // 获取不存在gitlab服务器项目id 1的项目名称。