#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 sonarqubeModel::getCacheFile();
cid=1
pid=1

使用正确的sonarqubeID,项目key获取缓存文件 >> 1

*/

$sonarqube = $tester->loadModel('sonarqube');

$sonarqubeID = 2;
$projectKey  = 'unit_test';
$result = $sonarqube->getCacheFile($sonarqubeID, $projectKey);
r(strPos($result, '/' . $sonarqubeID . '-' ) !== false) && p('') && e(1); //使用正确的sonarqubeID,项目key获取缓存文件
