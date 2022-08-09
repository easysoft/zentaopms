#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/pipeline.class.php';
su('admin');

/**

title=测试 pipelineModel->getPairs();
cid=1
pid=1

id为1且type为gitlab的pipeline名称 >> gitlab服务器
id为2且type为sonarqube的pipeline名称 >> sonarqube服务器
获取不存在的type >> 没有获取到数据

*/

$pipeline = new pipelineTest();

$data[0] = array('type' => 'gitlab', 'id' => 1);
$data[1] = array('type' => 'sonarqube', 'id' => 2);
$data[2] = array('type' => 'testType');

r($pipeline->getPairs($data[0])) && p() && e('gitlab服务器');    //id为1且type为gitlab的pipeline名称
r($pipeline->getPairs($data[1])) && p() && e('sonarqube服务器'); //id为2且type为sonarqube的pipeline名称
r($pipeline->getPairs($data[2])) && p() && e('没有获取到数据');  //获取不存在的type