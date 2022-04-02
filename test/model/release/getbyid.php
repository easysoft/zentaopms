#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/release.class.php';
su('admin');

/**

title=测试 releaseModel->getByID();
cid=1
pid=1

正常发布查询 >> 产品正常的正常的发布1,normal
停止维护查询 >> 项目停止维护的停止维护的里程碑发布6,terminate
无ID查询 >> 0
图片字段传字符串测试 >> 产品正常的正常的发布1,normal

*/
$releaseID = array('1', '6');

$release = new releaseTest();

r($release->getByIDTest($releaseID[0],true))   && p('name,status') && e('产品正常的正常的发布1,normal');                 //正常发布查询
r($release->getByIDTest($releaseID[1],false))  && p('name,status') && e('项目停止维护的停止维护的里程碑发布6,terminate');//停止维护查询
r($release->getByIDTest('',true))              && p('')            && e('0');                                            //无ID查询
r($release->getByIDTest($releaseID[0],'true')) && p('name,status') && e('产品正常的正常的发布1,normal');                 //图片字段传字符串测试