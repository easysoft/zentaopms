#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiGetNamespaces();
timeout=0
cid=1

- 通过gitlabID,获取GitLab namespace列表 @1
- 通过gitlabID,获取GitLab namespace数量 @1
- 通过错误的gitlabID获取namespace列表 @0
- 通过错误的gitlabID获取namespace列表数量 @0

*/

zdTable('pipeline')->gen(5);
zdTable('oauth')->gen(4);

$gitlab = $tester->loadModel('gitlab');

$gitlabID = 1;

$result = $gitlab->apiGetNamespaces($gitlabID);
r(isset($result[0]->path)) && p() && e('1'); //通过gitlabID,获取GitLab namespace列表
r(count($result) > 0)      && p() && e('1'); //通过gitlabID,获取GitLab namespace数量

$result = $gitlab->apiGetNamespaces(0);
r($result)        && p() && e('0'); //通过错误的gitlabID获取namespace列表
r(count($result)) && p() && e('0');  //通过错误的gitlabID获取namespace列表数量
