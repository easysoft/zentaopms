#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/pipeline.class.php';
su('admin');

/**

title=测试 pipelineModel->getByID();
cid=1
pid=1

获取id为1的name字段值 >> gitlab服务器

*/

$IDList = array('1','0');

$pipeline = new pipelineTest();

r($pipeline->getByIDTest($IDList[0])) && p('name') && e('gitlab服务器'); //获取id为1的name字段值
r($pipeline->getByIDTest($IDList[1])) && p()       && e();               //获取id为不存在的数据