#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/release.class.php';
su('admin');

/**

title=测试 releaseModel->getReleasedBuilds();
cid=1
pid=1

branch为'all'时数据查询查询 >> 1,3
branch为'0'时数据查询查询 >> 1,3
branch为'1'时数据查询查询 >> 0
branch为'2'时数据查询查询 >> 0
branch为''时数据查询查询 >> 1,3
产品ID为空查询 >> 0
产品ID不存在查询 >> 0

*/

$productID  = array('','1','10000');
$brachArray = array('all','0','1','2','');

$release = new releaseTest();
r($release->getReleasedBuildsTest($productID[1], $brachArray[0])) && p('0,1') && e('1,3'); //branch为'all'时数据查询查询
r($release->getReleasedBuildsTest($productID[1], $brachArray[1])) && p('0,1') && e('1,3'); //branch为'0'时数据查询查询
r($release->getReleasedBuildsTest($productID[1], $brachArray[2])) && p()      && e('0');   //branch为'1'时数据查询查询
r($release->getReleasedBuildsTest($productID[1], $brachArray[3])) && p()      && e('0');   //branch为'2'时数据查询查询
r($release->getReleasedBuildsTest($productID[1], $brachArray[4])) && p('0,1') && e('1,3'); //branch为''时数据查询查询
r($release->getReleasedBuildsTest($productID[0], $brachArray[0])) && p()      && e('0');   //产品ID为空查询
r($release->getReleasedBuildsTest($productID[2], $brachArray[0])) && p()      && e('0');   //产品ID不存在查询