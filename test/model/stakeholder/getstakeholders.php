#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/stakeholder.class.php';
su('admin');

/**

title=测试 stakeholderModel->getStakeholders();
cid=1
pid=1

敏捷项目干系人查询 >> 11,project
瀑布项目inside干系人查询 >> 31,产品经理10
outside干系人查询 >> project,用户1
关键干系人查询 >> 1,admin
项目集干系人查询 >> 1,program
敏捷项目干系人查询统计 >> 4
瀑布项目inside干系人查询统计 >> 3
outside干系人查询统计 >> 1
关键干系人查询统计 >> 1
项目集干系人查询统计 >> 3

*/
global $tester;
$stakeholder = $tester->loadModel('stakeholder');

$projectIDList = array('11', '31', '100', '1');
$browseType    = array('all', 'inside', 'outside', 'key', 'qt');
$orderBy       = array('id_desc', 'name_desc');

r($stakeholder->getStakeholders($projectIDList[0], $browseType[0], $orderBy[0]))        && p('1:objectID,objectType') && e('11,project');   //敏捷项目干系人查询
r($stakeholder->getStakeholders($projectIDList[1], $browseType[1], $orderBy[1]))        && p('92:objectID,name')      && e('31,产品经理10');//瀑布项目inside干系人查询
r($stakeholder->getStakeholders($projectIDList[2], $browseType[2], $orderBy[1]))        && p('301:objectType,name')   && e('project,用户1');//outside干系人查询
r($stakeholder->getStakeholders($projectIDList[0], $browseType[3], $orderBy[0]))        && p('1:key,name')            && e('1,admin');      //关键干系人查询
r($stakeholder->getStakeholders($projectIDList[3], $browseType[0], $orderBy[0]))        && p('2:objectID,objectType') && e('1,program');    //项目集干系人查询
r(count($stakeholder->getStakeholders($projectIDList[0], $browseType[0], $orderBy[0]))) && p()                        && e('4');            //敏捷项目干系人查询统计
r(count($stakeholder->getStakeholders($projectIDList[1], $browseType[1], $orderBy[1]))) && p()                        && e('3');            //瀑布项目inside干系人查询统计
r(count($stakeholder->getStakeholders($projectIDList[2], $browseType[2], $orderBy[1]))) && p()                        && e('1');            //outside干系人查询统计
r(count($stakeholder->getStakeholders($projectIDList[0], $browseType[3], $orderBy[0]))) && p()                        && e('1');            //关键干系人查询统计
r(count($stakeholder->getStakeholders($projectIDList[3], $browseType[0], $orderBy[0]))) && p()                        && e('3');            //项目集干系人查询统计