#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/doc.class.php';
su('admin');

/**

title=测试 docModel->getLibs();
cid=1
pid=1

全部文档库查询 >> 产品主库
api文档库查询 >> 0
产品文档库查询 >> 产品主库
项目文档库查询 >> 项目主库
执行文档库查询 >> 迭代主库
产品文档库关联查询 >> 正常产品1 / 产品主库
notdoc查询 >> 产品主库
额外文档库查询 >> 产品主库
产品单独查询 >> 产品主库
项目单独查询 >> 项目主库
执行单独查询 >> 迭代主库
全部文档库查询统计 >> 900
产品文档库查询统计 >> 100
项目文档库查询统计 >> 90
执行文档库查询统计 >> 630
产品文档库关联查询统计 >> 900
notdoc查询统计 >> 900
额外文档库查询统计 >> 2
产品单独查询统计 >> 1
项目单独查询统计 >> 1
执行单独查询统计 >> 1

*/
global $tester;
$doc = $tester->loadModel('doc');

$types = array('all', 'api', 'product', 'project', 'execution');
$extra = array('', 'withObject', 'notdoc');
$appendLibs = array('17', '1');
$objectIDs  = array('1', '11', '101', '0');

r($doc->getLibs($types[0], $extra[0], '', $objectIDs[3]))          && p('1')   && e('产品主库');            //全部文档库查询
r($doc->getLibs($types[1], $extra[0], '', $objectIDs[3]))          && p()      && e('0');                   //api文档库查询
r($doc->getLibs($types[2], $extra[0], '', $objectIDs[3]))          && p('10')  && e('产品主库');            //产品文档库查询
r($doc->getLibs($types[3], $extra[0], '', $objectIDs[3]))          && p('110') && e('项目主库');            //项目文档库查询
r($doc->getLibs($types[4], $extra[0], '', $objectIDs[3]))          && p('200') && e('迭代主库');            //执行文档库查询
r($doc->getLibs($types[0], $extra[1], '', $objectIDs[3]))          && p('1')   && e('正常产品1 / 产品主库');//产品文档库关联查询
r($doc->getLibs($types[0], $extra[2], '', $objectIDs[3]))          && p('1')   && e('产品主库');            //notdoc查询
r($doc->getLibs($types[1], $extra[0], $appendLibs, $objectIDs[3])) && p('1')   && e('产品主库');            //额外文档库查询
r($doc->getLibs($types[2], $extra[0], '', $objectIDs[0]))          && p('1')   && e('产品主库');            //产品单独查询
r($doc->getLibs($types[3], $extra[0], '', $objectIDs[1]))          && p('101') && e('项目主库');            //项目单独查询
r($doc->getLibs($types[4], $extra[0], '', $objectIDs[2]))          && p('191') && e('迭代主库');            //执行单独查询

r(count($doc->getLibs($types[0], $extra[0], '', $objectIDs[3])))          && p() && e('900');//全部文档库查询统计
r(count($doc->getLibs($types[2], $extra[0], '', $objectIDs[3])))          && p() && e('100');//产品文档库查询统计
r(count($doc->getLibs($types[3], $extra[0], '', $objectIDs[3])))          && p() && e('90'); //项目文档库查询统计
r(count($doc->getLibs($types[4], $extra[0], '', $objectIDs[3])))          && p() && e('630');//执行文档库查询统计
r(count($doc->getLibs($types[0], $extra[1], '', $objectIDs[3])))          && p() && e('900');//产品文档库关联查询统计
r(count($doc->getLibs($types[0], $extra[2], '', $objectIDs[3])))          && p() && e('900');//notdoc查询统计
r(count($doc->getLibs($types[1], $extra[0], $appendLibs, $objectIDs[3]))) && p() && e('2');  //额外文档库查询统计
r(count($doc->getLibs($types[2], $extra[0], '', $objectIDs[0])))          && p() && e('1');  //产品单独查询统计
r(count($doc->getLibs($types[3], $extra[0], '', $objectIDs[1])))          && p() && e('1');  //项目单独查询统计
r(count($doc->getLibs($types[4], $extra[0], '', $objectIDs[2])))          && p() && e('1');  //执行单独查询统计