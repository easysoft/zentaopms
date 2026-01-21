#!/usr/bin/env php
<?php

/**

title=测试 spaceModel::getListByAccount();
timeout=0
cid=16027

- 用户为空 @0
- 用户为admin第1条的name属性 @space1
- 用户不存在 @0
- 用户为test1第2条的account属性 @test1
- 查询用户为test2的空间总数 @3
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zendata('ops_space')->gen(10);
zendata('ops_spaceuser')->gen(10);

su('admin');
$accountList = array('', 'admin', 'xmjl01', 'test1', 'test2');

global $tester;
$spaceModel = $tester->loadModel('space');

r($spaceModel->getListByAccount($accountList[0])) && p()            && e('0');      //用户为空
r($spaceModel->getListByAccount($accountList[1])) && p('1:name')    && e('space1'); //用户为admin
r($spaceModel->getListByAccount($accountList[2])) && p()            && e('0');      //用户不存在
r($spaceModel->getListByAccount($accountList[3])) && p('2:account') && e('test1');  //用户为test1

r(count($spaceModel->getListByAccount($accountList[4]))) && p() && e('3');     //查询用户为test2的空间总数
