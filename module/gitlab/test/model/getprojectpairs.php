#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试gitlabModel->getProjectPairs();
timeout=0
cid=1

- 获取gitlab服务器1的项目1的名称。属性1 @GitLab Instance / Monitoring
- 获取gitlab服务器1的项目2的名称。属性2 @GitLab Instance / testHtml
- 获取GitLab服务器不存在的项目名称。属性1 @0

*/

$gitlab = new gitlabTest();

r($gitlab->getProjectPairsTest($gitlabID = 1))  && p('1') && e('GitLab Instance / Monitoring');     // 获取gitlab服务器1的项目1的名称。
r($gitlab->getProjectPairsTest($gitlabID = 1))  && p('2') && e('GitLab Instance / testHtml');     // 获取gitlab服务器1的项目2的名称。
r($gitlab->getProjectPairsTest($gitlabID = 10)) && p('1') && e('0'); // 获取GitLab服务器不存在的项目名称。