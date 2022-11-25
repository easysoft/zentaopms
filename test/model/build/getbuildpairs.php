#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/build.class.php';
su('admin');

/**

title=测试 buildModel->getBuildPairs();
cid=1
pid=1

数组产品执行版本查询 >> 执行版本版本17
单长产品执行版本查询 >> 执行版本版本11
项目执行版本查询 >> 主干
数组产品执行版本查询统计 >> 6
单长产品执行版本查询统计 >> 4
项目执行版本查询统计 >> 1

*/

$products     = array('0', '1', '7');
$branch       = 'all';
$params       = array('noterminate','nodone', 'noempty', 'notrunk');
$objectID     = array('0', '7', '17');
$objectType   = array('execution', 'project', '');
$buildIdList  = array('1', '7', '17');
$replace      = array(true, false);
$count        = array('0', '1');

$build = new buildTest();

r($build->getBuildPairsTest($count[0], $products, $branch, $params[0], $objectID[0], $objectType[0], '',$replace[0]))              && p('17')    && e('执行版本版本17');//数组产品执行版本查询
r($build->getBuildPairsTest($count[0], $products[1], $branch, $params[1], $objectID[0], $objectType[0], $buildIdList,$replace[0])) && p('11')    && e('执行版本版本11');//单长产品执行版本查询
r($build->getBuildPairsTest($count[0], $products, $branch, $params[2], $objectID[1], $objectType[1], $buildIdList,$replace[0]))    && p('trunk') && e('主干');          //项目执行版本查询
r($build->getBuildPairsTest($count[1], $products, $branch, $params[0], $objectID[0], $objectType[0], '',$replace[0]))              && p()        && e('6');             //数组产品执行版本查询统计
r($build->getBuildPairsTest($count[1], $products[1], $branch, $params[1], $objectID[0], $objectType[0], $buildIdList,$replace[0])) && p()        && e('4');             //单长产品执行版本查询统计
r($build->getBuildPairsTest($count[1], $products, $branch, $params[2], $objectID[1], $objectType[1], $buildIdList,$replace[0]))    && p()        && e('1');             //项目执行版本查询统计
