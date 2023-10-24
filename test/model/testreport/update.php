#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testreport.class.php';
su('admin');

/**

title=测试 testreportModel->update();
cid=1
pid=1

正常修改 >> 1,正常修改
负责人为空测试 >> 『负责人』不能为空。
参与人员为空测试 >> 1,参与人员为空测试,
标题为空测试 >> 『标题』不能为空。

*/
$reportID = '1';
$members  = array('dev1','dev11');
$cases    = array(1,2,3,4);
$title    = array('正常修改', '负责人为空测试', '参与人员为空测试', '');

$testreport = new testreportTest();

$normalReport = array('owner' => 'user3', 'members' => $members, 'title' => $title[0], 'report' => '', 'cases' => $cases);
$noOwner      = array('owner' => '',      'members' => $members, 'title' => $title[1], 'report' => '', 'cases' => $cases);
$noMembers    = array('owner' => 'user3', 'members' => '',       'title' => $title[2], 'report' => '', 'cases' => $cases);
$noTitle      = array('owner' => 'user3', 'members' => $members, 'title' => $title[3], 'report' => '', 'cases' => $cases);

r($testreport->updateTest($reportID, $normalReport)) && p('id,title')         && e('1,正常修改');           //正常修改
r($testreport->updateTest($reportID, $noOwner))      && p('owner:0')          && e('『负责人』不能为空。'); //负责人为空测试
r($testreport->updateTest($reportID, $noMembers))    && p('id,title,members') && e('1,参与人员为空测试,');  //参与人员为空测试
r($testreport->updateTest($reportID, $noTitle))      && p('title:0')          && e('『标题』不能为空。');   //标题为空测试

