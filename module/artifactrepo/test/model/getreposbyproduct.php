#!/usr/bin/env php
<?php
/**

title=测试 artifactrepoModel->getReposByProduct();
cid=1

- 获取产品ID为0的制品库信息 @0
- 获取产品ID为1的制品库信息
 - 第1条的name属性 @制品库1
 - 第1条的products属性 @,1,
 - 第1条的serverID属性 @1
 - 第1条的repoName属性 @代码库1
 - 第1条的format属性 @~~
 - 第1条的type属性 @~~
 - 第1条的status属性 @~~
 - 第1条的pipelineID属性 @1
 - 第1条的url属性 @https://nexus3dev.qc.oop.cc//repository/代码库1
- 获取产品ID为2的制品库信息 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/artifactrepo.class.php';

zdTable('user')->gen(5);
zdTable('pipeline')->config('pipeline')->gen(1);

$artifactrepoTable = zdTable('artifactrepo')->config('artifactrepo');
$artifactrepoTable->products->range('`,1,`');
$artifactrepoTable->gen(1);

$productIdList = array(0, 1, 2);

$artifactrepoTester = new artifactrepoTest();
r($artifactrepoTester->getReposByProductTest($productIdList[0])) && p()                                                                           && e('0');                                                                                // 获取产品ID为0的制品库信息
r($artifactrepoTester->getReposByProductTest($productIdList[1])) && p('1:name|products|serverID|repoName|format|type|status|pipelineID|url', '|') && e('制品库1|,1,|1|代码库1|~~|~~|~~|1|https://nexus3dev.qc.oop.cc//repository/代码库1'); // 获取产品ID为1的制品库信息
r($artifactrepoTester->getReposByProductTest($productIdList[2])) && p()                                                                           && e('0');                                                                                // 获取产品ID为2的制品库信息
