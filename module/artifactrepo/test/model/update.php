#!/usr/bin/env php
<?php
/**

title=测试 artifactrepoModel->update();
cid=1

- 测试修改ID为0的制品库名称 @0
- 测试修改ID为1的制品库名称和代码库名称
 - 第0条的field属性 @name
 - 第0条的old属性 @制品库1
 - 第0条的new属性 @修改制品库名称2
- 测试修改ID为1的制品库名称和服务器ID
 - 第1条的field属性 @serverID
 - 第1条的old属性 @1
 - 第1条的new属性 @2
- 测试修改ID为1的制品库名称和类型
 - 第1条的field属性 @type
 - 第1条的old属性 @~~
 - 第1条的new属性 @gitlab
- 测试修改ID为1的制品库名称和状态
 - 第1条的field属性 @status
 - 第1条的old属性 @~~
 - 第1条的new属性 @online
- 测试修改ID为2的制品库名称为重复名称第name条的0属性 @『名称』已经有『修改制品库名称5』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。
- 测试修改ID为6的制品库名称 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/artifactrepo.class.php';

zdTable('user')->gen(5);
zdTable('artifactrepo')->config('artifactrepo')->gen(5);

$names     = array('修改制品库名称1', '修改制品库名称2', '修改制品库名称3', '修改制品库名称4', '修改制品库名称5');
$repoName  = '修改代码库名称';
$serverID  = 2;
$type      = 'gitlab';
$status    = 'online';
$idList    = array(0, 1, 2 ,6);

$changeName     = array('name' => $names[0]);
$changeRepoName = array('name' => $names[1], 'repoName' => $repoName);
$changeServerID = array('name' => $names[2], 'serverID' => $serverID);
$changeType     = array('name' => $names[3], 'type' => $type);
$changeStatus   = array('name' => $names[4], 'status' => $status);
$repeatName     = array('name' => $names[4]);

$artifactrepoTester = new artifactrepoTest();
r($artifactrepoTester->updateTest($idList[0], $changeName))     && p()                  && e('0');                                                                                                   // 测试修改ID为0的制品库名称
r($artifactrepoTester->updateTest($idList[1], $changeRepoName)) && p('0:field,old,new') && e('name,制品库1,修改制品库名称2');                                                                        // 测试修改ID为1的制品库名称和代码库名称
r($artifactrepoTester->updateTest($idList[1], $changeServerID)) && p('1:field,old,new') && e('serverID,1,2');                                                                                        // 测试修改ID为1的制品库名称和服务器ID
r($artifactrepoTester->updateTest($idList[1], $changeType))     && p('1:field,old,new') && e('type,~~,gitlab');                                                                                      // 测试修改ID为1的制品库名称和类型
r($artifactrepoTester->updateTest($idList[1], $changeStatus))   && p('1:field,old,new') && e('status,~~,online');                                                                                    // 测试修改ID为1的制品库名称和状态
r($artifactrepoTester->updateTest($idList[2], $repeatName))     && p('name:0')          && e('『名称』已经有『修改制品库名称5』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 测试修改ID为2的制品库名称为重复名称
r($artifactrepoTester->updateTest($idList[3], $changeName))     && p()                  && e('0');                                                                                                   // 测试修改ID为6的制品库名称
