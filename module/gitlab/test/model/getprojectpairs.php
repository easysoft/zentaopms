#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';
su('admin');

/**

title=测试gitlabModel->getProjectPairs();
timeout=0
cid=16656

- 获取gitlab服务器1的项目1的名称。属性1 @GitLab Instance / Monitoring
- 获取gitlab服务器1的项目2的名称。属性2 @GitLab Instance / testHtml
- 获取gitlab服务器1的项目3的名称。属性3 @Administrator / unittest1
- 获取gitlab服务器1的项目4的名称。属性4 @GitLab Instance / privateProject
- 获取GitLab服务器不存在的项目名称。属性1 @0

*/

$gitlab = new gitlabTest();

$normalResult   = $gitlab->getProjectPairsTest($gitlabID = 1);
$notExistResult = $gitlab->getProjectPairsTest($gitlabID = 10);
r($normalResult)   && p('1') && e('GitLab Instance / Monitoring');     // 获取gitlab服务器1的项目1的名称。
r($normalResult)   && p('2') && e('GitLab Instance / testHtml');       // 获取gitlab服务器1的项目2的名称。
r($normalResult)   && p('3') && e('Administrator / unittest1');        // 获取gitlab服务器1的项目3的名称。
r($normalResult)   && p('4') && e('GitLab Instance / privateProject'); // 获取gitlab服务器1的项目4的名称。
r($notExistResult) && p('1') && e('0');                                // 获取GitLab服务器不存在的项目名称。
