#!/usr/bin/env php
<?php
/**

title=测试 devopsspaceModel::getPairs();
timeout=0
cid=0

- 用户为空 @space1
- 用户为admin第1条的name属性 @space1
- 用户不存在 @0
- 用户为test1第2条的account属性 @space1
- 查询空间总数 @10
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zendata('ops_space')->gen(10);
zendata('ops_spaceuser')->gen(10);

su('admin');
$accountList = array('', 'admin', 'xmjl01', 'test1', 'test2');

global $tester;
$devopsspaceModel = $tester->loadModel('devopsspace');

r($devopsspaceModel->getPairs($accountList[0])) && p('1') && e('space1'); //用户为空
r($devopsspaceModel->getPairs($accountList[1])) && p('1') && e('space1'); //用户为admin
$tester->app->user->admin = false;
r($devopsspaceModel->getPairs($accountList[2])) && p('6') && e('0'); //用户不存在
r($devopsspaceModel->getPairs($accountList[3])) && p('1') && e('space1'); //用户为test1

$tester->app->user->admin = true;
r(count($devopsspaceModel->getPairs($accountList[0]))) && p() && e('10');     //查询空间总数
