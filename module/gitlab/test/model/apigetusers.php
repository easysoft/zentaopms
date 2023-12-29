#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiGetUsers();
timeout=0
cid=1

- 通过gitlabID,onlyLinked,获取GitLab用户列表 @1
- 通过gitlabID,onlyLinked,获取GitLab用户数量 @1
- 获取GitLab用户已做了关联的用户列表第0条的account属性 @user6
- 获取GitLab用户已做了关联的用户列表数量 @2
- 通过gitlabID,onlyLinked,按用户名升序获取GitLab用户列表第0条的account属性 @root

*/

zdTable('pipeline')->gen(5);
zdTable('oauth')->config('oauth')->gen(4);

$gitlab = $tester->loadModel('gitlab');

$gitlabID   = 1;
$onlyLinked = false;
$result     = $gitlab->apiGetUsers($gitlabID, $onlyLinked);
r(isset($result[0]->account)) && p() && e('1'); //通过gitlabID,onlyLinked,获取GitLab用户列表
r(count($result) > 0)         && p() && e('1'); //通过gitlabID,onlyLinked,获取GitLab用户数量

$gitlabID   = 1;
$onlyLinked = true;
$result = $gitlab->apiGetUsers($gitlabID, $onlyLinked);
r($result)        && p('0:account') && e('user6'); //获取GitLab用户已做了关联的用户列表
r(count($result)) && p()            && e('2');     //获取GitLab用户已做了关联的用户列表数量

$gitlabID   = 1;
$onlyLinked = false;
$orderBy    = 'id_asc';
$result     = $gitlab->apiGetUsers($gitlabID, $onlyLinked, $orderBy);
r($result) && p('0:account') && e('root'); //通过gitlabID,onlyLinked,按用户名升序获取GitLab用户列表