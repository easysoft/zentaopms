#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/stakeholder.class.php';
su('admin');

/**

title=测试 stakeholderModel->getStakeHolderPairs();
cid=1
pid=1

敏捷项目查询干系人 >> 开发8
瀑布项目查询干系人 >> 产品经理8
外部干系人查询 >> 用户1
项目集干系人查询 >> 测试7
敏捷项目查询干系人统 计 >> 4
瀑布项目查询干系人统计 >> 3
外部干系人查询统计 >> 3
项目集干系人查询统计 >> 3

*/
global $tester;
$stakeholder = $tester->loadModel('stakeholder');

$projectIDList = array('11', '31', '100', '1');

r($stakeholder->getStakeHolderPairs($projectIDList[0])) && p('test8')    && e('开发8');    //敏捷项目查询干系人
r($stakeholder->getStakeHolderPairs($projectIDList[1])) && p('pm8')      && e('产品经理8');//瀑布项目查询干系人
r($stakeholder->getStakeHolderPairs($projectIDList[2])) && p('outside1') && e('用户1');    //外部干系人查询
r($stakeholder->getStakeHolderPairs($projectIDList[3])) && p('user7')    && e('测试7');    //项目集干系人查询
r(count($stakeholder->getStakeHolderPairs($projectIDList[0]))) && p()    && e('4');        //敏捷项目查询干系人统 计
r(count($stakeholder->getStakeHolderPairs($projectIDList[1]))) && p()    && e('3');        //瀑布项目查询干系人统计
r(count($stakeholder->getStakeHolderPairs($projectIDList[2]))) && p()    && e('3');        //外部干系人查询统计
r(count($stakeholder->getStakeHolderPairs($projectIDList[3]))) && p()    && e('3');        //项目集干系人查询统计