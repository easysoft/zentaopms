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
项目版本停止维护数据查询 >> 0
执行版本正常发布数据查询 >> po1
执行版本停止发布数据查询 >> 0
buildID为空查询 >> 0
releaseID为空查询 >> 0
buildID不存在查询 >> po1
releaseID不存在查询 >> 0

*/

$releaseID  = array('1','6','20','');

$release = new releaseTest();

r($release->getNotifyPersonsTest($releaseID[0])) && p('po1') && e('po1');//项目版本正常发布数据查询
r($release->getNotifyPersonsTest($releaseID[1])) && p('po1') && e('0');  //项目版本停止维护数据查询
r($release->getNotifyPersonsTest($releaseID[0])) && p('po1') && e('po1');//执行版本正常发布数据查询
r($release->getNotifyPersonsTest($releaseID[1])) && p('po1') && e('0');  //执行版本停止发布数据查询
r($release->getNotifyPersonsTest($releaseID[1])) && p('po1') && e('0');  //buildID为空查询
r($release->getNotifyPersonsTest($releaseID[3])) && p('po1') && e('0');  //releaseID为空查询
r($release->getNotifyPersonsTest($releaseID[0])) && p('po1') && e('po1');//buildID不存在查询
r($release->getNotifyPersonsTest($releaseID[2])) && p('po1') && e('0');  //releaseID不存在查询
