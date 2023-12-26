#!/usr/bin/env php
<?php
/**

title=测试 artifactrepoModel->getByID();
cid=1

- 获取ID为0的制品库信息 @0
- 获取ID为1的制品库信息
 - 属性name @制品库1
 - 属性products @1
 - 属性serverID @1
 - 属性repoName @代码库1
 - 属性format @~~
 - 属性type @~~
 - 属性status @~~
- 获取ID为2的制品库信息 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/artifactrepo.class.php';

zdTable('user')->gen(5);
zdTable('pipeline')->config('pipeline')->gen(6);
zdTable('artifactrepo')->config('artifactrepo')->gen(1);

$idList = array(0, 1, 2);

$artifactrepoTester = new artifactrepoTest();
r($artifactrepoTester->getByIDTest($idList[0])) && p()                                                     && e('0');                            // 获取ID为0的制品库信息
r($artifactrepoTester->getByIDTest($idList[1])) && p('name,products,serverID,repoName,format,type,status') && e('制品库1,1,1,代码库1,~~,~~,~~'); // 获取ID为1的制品库信息
r($artifactrepoTester->getByIDTest($idList[2])) && p()                                                     && e('0');                            // 获取ID为2的制品库信息
