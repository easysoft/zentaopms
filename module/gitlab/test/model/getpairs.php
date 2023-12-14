#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试gitlabModel->gitPairs();
timeout=0
cid=1

- 获取GitLab id为1的名字。属性1 @GitLab服务器
- 获取GitLab 列表数量。 @1

*/

$gitlab = new gitlabTest();

$gitlabPairs = $gitlab->getPairs();
r($gitlabPairs)        && p(1) && e('GitLab服务器'); // 获取GitLab id为1的名字。
r(count($gitlabPairs)) && p()  && e('1');            // 获取GitLab 列表数量。