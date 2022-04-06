#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/release.class.php';
su('admin');

/**

title=测试 releaseModel->getNotifyPersons();
cid=1
pid=1

项目版本正常发布数据查询 >> po1
项目版本停止维护数据查询 >> po1
执行版本正常发布数据查询 >> po1
执行版本停止发布数据查询 >> po1
buildID为空查询 >> po1
releaseID为空查询 >> po1
buildID不存在查询 >> po1
releaseID不存在查询 >> po1

*/

$notifyList = 'PO, QD, feedback, SC, ET, PT';
$productID  = 1;
$buildID    = array('1','10','30','');
$releaseID  = array('1','6','20','');

$release = new releaseTest();

r($release->getNotifyPersonsTest($notifyList, $productID, $buildID[0], $releaseID[0])) && p('po1') && e('po1');//项目版本正常发布数据查询
r($release->getNotifyPersonsTest($notifyList, $productID, $buildID[0], $releaseID[1])) && p('po1') && e('po1');//项目版本停止维护数据查询
r($release->getNotifyPersonsTest($notifyList, $productID, $buildID[1], $releaseID[0])) && p('po1') && e('po1');//执行版本正常发布数据查询
r($release->getNotifyPersonsTest($notifyList, $productID, $buildID[1], $releaseID[1])) && p('po1') && e('po1');//执行版本停止发布数据查询
r($release->getNotifyPersonsTest($notifyList, $productID, $buildID[3], $releaseID[1])) && p('po1') && e('po1');//buildID为空查询
r($release->getNotifyPersonsTest($notifyList, $productID, $buildID[1], $releaseID[3])) && p('po1') && e('po1');//releaseID为空查询
r($release->getNotifyPersonsTest($notifyList, $productID, $buildID[2], $releaseID[0])) && p('po1') && e('po1');//buildID不存在查询
r($release->getNotifyPersonsTest($notifyList, $productID, $buildID[0], $releaseID[2])) && p('po1') && e('po1');//releaseID不存在查询