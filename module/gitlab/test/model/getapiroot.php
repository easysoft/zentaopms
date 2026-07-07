#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::getApiRoot();
timeout=0
cid=16647

- 不存在的服务器 @0
- 错误的服务器 @0
- 正确的服务器 @https://gitlabdev.qc.oop.cc/api/v4%s?private_token=glpat-b8Sa1pM9k9ygxMZYPN6w
- 管理员获取接口地址 @https://gitlabdev.qc.oop.cc/api/v4%s?private_token=glpat-b8Sa1pM9k9ygxMZYPN6w
- 普通用户获取接口地址 @https://gitlabdev.qc.oop.cc/api/v4%s?private_token=glpat-b8Sa1pM9k9ygxMZYPN6w&sudo=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('pipeline')->gen(5);
zenData('oauth')->loadYaml('oauth')->gen(1);
su('admin');

$gitlabTest = new gitlabModelTest();

$failID   = 10;
$gitlabID = 1;
$giteaID  = 4;

r($gitlabTest->getApiRootTest($failID))  && p() && e('0');
r($gitlabTest->getApiRootTest($giteaID)) && p() && e('0');

r($gitlabTest->getApiRootTest($gitlabID, false)) && p() && e('https://gitlabdev.qc.oop.cc/api/v4%s?private_token=glpat-b8Sa1pM9k9ygxMZYPN6w');
r($gitlabTest->getApiRootTest($gitlabID))        && p() && e('https://gitlabdev.qc.oop.cc/api/v4%s?private_token=glpat-b8Sa1pM9k9ygxMZYPN6w');

su('user3', false);
r($gitlabTest->getApiRootTest($gitlabID)) && p() && e('https://gitlabdev.qc.oop.cc/api/v4%s?private_token=glpat-b8Sa1pM9k9ygxMZYPN6w&sudo=1');
