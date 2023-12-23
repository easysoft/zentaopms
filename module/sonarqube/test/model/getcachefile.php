#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/sonarqube.class.php';
su('admin');

/**

title=测试 sonarqubeModel::getCacheFile();
timeout=0
cid=1

- 使用正确的sonarqubeID,项目key获取缓存文件 @1

*/

zdTable('pipeline')->config('pipeline')->gen(5);

$sonarqubeID = 2;
$projectKey  = 'unit_test';

$sonarqube = new sonarqubeTest();
r($sonarqube->getCacheFileTest($sonarqubeID, $projectKey)) && p('') && e(1); //使用正确的sonarqubeID,项目key获取缓存文件