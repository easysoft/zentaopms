#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 giteaModel::getGiteaGroups();
timeout=0
cid=1

- 使用正确的giteaID查询群组第0条的text属性 @org1
- 使用正确的giteaID查询群组数量 @1
- 使用错误的giteaID查询 @0

*/

zdTable('pipeline')->gen(5);

$repo = $tester->loadModel('repo');

$giteaID = 4;

$result = $repo->getGiteaGroups($giteaID);
r($result)                  && p('0:text') && e('org1'); //使用正确的giteaID查询群组
r(count($result) > 1)       && p()         && e('1');    //使用正确的giteaID查询群组数量
r($repo->getGiteaGroups(0)) && p()         && e('0');    //使用错误的giteaID查询