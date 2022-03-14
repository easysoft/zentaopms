#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/project.class.php';

/**

title=测试 projectModel::getProjectsConsumed;
cid=1
pid=1

var_dump(->getConsumed());die; >> 21.0
 >> 28.0
 >> 35.0

*/

$projects = new Project('admin');

$getConsumed = array(11, 12, 13);

//var_dump($projects->getConsumed($getConsumed));die;
r($projects->getConsumed($getConsumed)) && p('11:totalConsumed') && e('21.0');
r($projects->getConsumed($getConsumed)) && p('12:totalConsumed') && e('28.0');
r($projects->getConsumed($getConsumed)) && p('13:totalConsumed') && e('35.0');