#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/sonarqube.class.php';
su('admin');

/**

title=测试 sonarqubeModel::getCacheFile();
cid=1
pid=1



*/

$sonarqubeID = 2;
$projectKey  = 'unit_test';

$sonarqube = new sonarqubeTest();
r($sonarqube->getCacheFileTest($sonarqubeID, $projectKey)) && p('') && e(1); //使用正确的sonarqubeID,项目key获取缓存文件