#!/usr/bin/env php
<?php
/**

title=测试 artifactrepoModel->getByID();
cid=1

- 将ID为0的制品库状态更新为online @0
- 将ID为1的制品库状态更新为online
 - 第0条的field属性 @status
 - 第0条的old属性 @offline
 - 第0条的new属性 @online
- 将ID为2的制品库状态更新为offline
 - 第0条的field属性 @status
 - 第0条的old属性 @online
 - 第0条的new属性 @offline
- 将ID为0的制品库状态更新为offline @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/artifactrepo.class.php';

zdTable('user')->gen(5);
$artifactrepoTable = zdTable('artifactrepo')->config('artifactrepo');
$artifactrepoTable->status->range('offline,online');
$artifactrepoTable->gen(2);

$idList     = array(0, 1, 2, 3);
$statusList = array('online', 'offline');

$artifactrepoTester = new artifactrepoTest();
r($artifactrepoTester->updateStatusTest($idList[0], $statusList[0])) && p()                  && e('0');                     // 将ID为0的制品库状态更新为online
r($artifactrepoTester->updateStatusTest($idList[1], $statusList[0])) && p('0:field,old,new') && e('status,offline,online'); // 将ID为1的制品库状态更新为online
r($artifactrepoTester->updateStatusTest($idList[2], $statusList[1])) && p('0:field,old,new') && e('status,online,offline'); // 将ID为2的制品库状态更新为offline
r($artifactrepoTester->updateStatusTest($idList[3], $statusList[1])) && p()                  && e('0');                     // 将ID为0的制品库状态更新为offline
