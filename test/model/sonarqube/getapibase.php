#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 sonarqubeModel::getApiBase();
cid=1
pid=1

通过sonarqubeID,获取SonarQube url和header    >> http://192.168.1.161:59001/api/%s
当sonarqubeID为0时,获取SonarQube url和header >> return empty

*/

$sonarqube = $tester->loadModel('sonarqube');

$sonarqubeID = 2;
list($apiRoot, $header) = $sonarqube->getApiBase($sonarqubeID);
r($apiRoot) && p() && e('http://192.168.1.161:59001/api/%s'); //通过sonarqubeID,获取SonarQube url和header

$sonarqubeID = 0;
list($apiRoot, $header) = $sonarqube->getApiBase($sonarqubeID);
if(empty($apiRoot)) $result = 'return empty';
r($result) && p() && e('return empty'); //当sonarqubeID为0时,获取SonarQube url和header
