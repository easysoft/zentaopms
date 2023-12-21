#!/usr/bin/env php
<?php

/**

title=测试 giteaModel::checkTokenAccess();
timeout=0
cid=1

- 不存在的服务器 @0
- 错误的服务器 @0
- 正确的服务器 @https://giteadev.qc.oop.cc/api/v1%s?token=6149a6013047301b116389d50db5cbf599772082
- 管理员获取接口地址 @https://giteadev.qc.oop.cc/api/v1%s?token=6149a6013047301b116389d50db5cbf599772082
- 普通用户获取接口地址 @https://giteadev.qc.oop.cc/api/v1%s?token=6149a6013047301b116389d50db5cbf599772082&sudo=5

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('pipeline')->gen(5);
zdTable('oauth')->config('oauth')->gen(1);
su('admin');

global $tester;
$gitea = $tester->loadModel('gitea');

$failID   = 10;
$gitlabID = 1;
$giteaID  = 4;

r($gitea->getApiRoot($failID))   && p() && e('0'); // 不存在的服务器
r($gitea->getApiRoot($gitlabID)) && p() && e('0'); // 错误的服务器

r($gitea->getApiRoot($giteaID, false))  && p() && e('https://giteadev.qc.oop.cc/api/v1%s?token=6149a6013047301b116389d50db5cbf599772082'); // 正确的服务器
r($gitea->getApiRoot($giteaID))         && p() && e('https://giteadev.qc.oop.cc/api/v1%s?token=6149a6013047301b116389d50db5cbf599772082'); // 管理员获取接口地址

su('user1', false);
r($gitea->getApiRoot($giteaID)) && p() && e('https://giteadev.qc.oop.cc/api/v1%s?token=6149a6013047301b116389d50db5cbf599772082&sudo=5'); // 普通用户获取接口地址