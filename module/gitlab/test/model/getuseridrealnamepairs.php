#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试gitlabModel->getUserIdRealnamePairs();
timeout=0
cid=1

- 获取GitLab服务器1用户id为1的绑定的用户真实姓名。属性1 @用户3
- 获取GitLab服务器1用户id为3的绑定的用户真实姓名。属性3 @用户5
- 获取GitLab服务器不存在用户id为3的绑定的用户真实姓名。属性1 @0

*/

zdTable('user')->gen(10);
zdTable('oauth')->gen(5);

$gitlab = new gitlabTest();

r($gitlab->getUserIdRealnamePairsTest($gitlabID = 1))  && p('1') && e('用户3'); // 获取GitLab服务器1用户id为1的绑定的用户真实姓名。
r($gitlab->getUserIdRealnamePairsTest($gitlabID = 1))  && p('3') && e('用户5'); // 获取GitLab服务器1用户id为3的绑定的用户真实姓名。
r($gitlab->getUserIdRealnamePairsTest($gitlabID = 10)) && p('1') && e('0'); // 获取GitLab服务器不存在用户id为3的绑定的用户真实姓名。