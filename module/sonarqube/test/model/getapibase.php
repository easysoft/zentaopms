#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/sonarqube.class.php';
su('admin');

/**

title=测试 sonarqubeModel::getApiBase();
timeout=0
cid=1

- 通过sonarqubeID,获取SonarQube url和header @https://sonardev.qc.oop.cc/api/%s
- 当sonarqubeID为0时,获取SonarQube url和header @return empty

*/

zdTable('pipeline')->gen(5);

$sonarqubeID = 2;

$sonarqube = new sonarqubeTest();
r($sonarqube->getApiBaseTest($sonarqubeID)) && p() && e('https://sonardev.qc.oop.cc/api/%s'); //通过sonarqubeID,获取SonarQube url和header
r($sonarqube->getApiBaseTest(0))            && p() && e('return empty');                      //当sonarqubeID为0时,获取SonarQube url和header