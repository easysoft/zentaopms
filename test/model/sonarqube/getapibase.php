#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/sonarqube.class.php';
su('admin');

/**

title=测试 sonarqubeModel::getApiBase();
cid=1
pid=1

通过sonarqubeID,获取SonarQube url和header >> http://192.168.1.161:59001/api/%s
当sonarqubeID为0时,获取SonarQube url和header >> return empty

*/

$sonarqubeID = 2;

$sonarqube = new sonarqubeTest();
r($sonarqube->getApiBaseTest($sonarqubeID)) && p() && e('http://192.168.1.161:59001/api/%s'); //通过sonarqubeID,获取SonarQube url和header
r($sonarqube->getApiBaseTest(0))            && p() && e('return empty');                      //当sonarqubeID为0时,获取SonarQube url和header