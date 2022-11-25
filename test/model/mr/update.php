#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 mrModel::update();
cid=0
pid=0

POST数据正确修改MR描述 >> success
使用title为空的数据mr请求 >> 『名称』不能为空。

*/

$mrModel = $tester->loadModel('mr');
$MRID    = 1;

$_POST = array();
$_POST['targetBranch']       = 'master';
$_POST['title']              = 'Test MR';
$_POST['description']        = '2022-01-31 23:59:59';
$_POST['repoID']             = 1;
$_POST['assignee']           = '';
$_POST['removeSourceBranch'] = '0';
$result = $mrModel->update($MRID);
if($result['result'] == 'success') $result = 'success';
r($result) && p() && e('success'); //POST数据正确修改MR描述

$_POST['title'] = '';
$result = $mrModel->update($MRID);
r($result) && p('message[title]:0') && e('『名称』不能为空。'); //使用title为空的数据mr请求