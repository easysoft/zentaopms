#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 sonarqubeModel::getByID();
cid=1
pid=1

通过id获取sonarqube服务器     >> sonarqube服务器
通过空的id获取sonarqube服务器 >> return empty

*/

$sonarqube = $tester->loadModel('sonarqube');

$sonarqubeID     = 2;
$sonarqubeServer = $sonarqube->getByID($sonarqubeID);
r($sonarqubeServer) && p('name') && e('sonarqube服务器'); //通过id获取sonarqube服务器

$sonarqubeID     = 0;
$sonarqubeServer = $sonarqube->getByID($sonarqubeID);
if(empty($sonarqubeServer)) $result = 'return empty';
r($result) && p() && e('return empty'); //通过空的id获取sonarqube服务器
