#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::checkTokenAccess();
timeout=0
cid=1

- 不存在的服务器 @0
- 错误的服务器 @0
- 正确的服务器 @https://gitlabdev.qc.oop.cc/api/v4%s?private_token=glpat-b8Sa1pM9k9ygxMZYPN6w
- 管理员获取接口地址 @https://gitlabdev.qc.oop.cc/api/v4%s?private_token=glpat-b8Sa1pM9k9ygxMZYPN6w
- 普通用户获取接口地址 @https://gitlabdev.qc.oop.cc/api/v4%s?private_token=glpat-b8Sa1pM9k9ygxMZYPN6w&sudo=1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('pipeline')->gen(5);
zdTable('oauth')->config('oauth')->gen(1);
su('admin');

global $tester;
$gitlab = $tester->loadModel('gitlab');

$failID   = 10;
$gitlabID = 1;
$giteaID  = 4;

r($gitlab->getApiRoot($failID))  && p() && e('0'); // 不存在的服务器
r($gitlab->getApiRoot($giteaID)) && p() && e('0'); // 错误的服务器

r($gitlab->getApiRoot($gitlabID, false))  && p() && e('https://gitlabdev.qc.oop.cc/api/v4%s?private_token=glpat-b8Sa1pM9k9ygxMZYPN6w'); // 正确的服务器
r($gitlab->getApiRoot($gitlabID))         && p() && e('https://gitlabdev.qc.oop.cc/api/v4%s?private_token=glpat-b8Sa1pM9k9ygxMZYPN6w'); // 管理员获取接口地址

su('user3', false);
r($gitlab->getApiRoot($gitlabID)) && p() && e('https://gitlabdev.qc.oop.cc/api/v4%s?private_token=glpat-b8Sa1pM9k9ygxMZYPN6w&sudo=1'); // 普通用户获取接口地址