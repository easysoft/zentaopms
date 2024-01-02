#!/usr/bin/env php
<?php

/**

title=测试 sonarqubeModel::getProjectPairs();
cid=0

- 传入空参数。 @0
- 检查项目数。 @4
- 检查其中某个项目。属性bendi @本地项目

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('pipeline')->config('pipeline')->gen(5);

$sonarqubeID = 2;

global $tester;
$sonarqube = $tester->loadModel('sonarqube');
r($sonarqube->getProjectPairs(0)) && p() && e('0'); //传入空参数。

$projects = $sonarqube->getProjectPairs($sonarqubeID);
r(count($projects))  && p()        && e('4');         //检查项目数。
r($projects)         && p('bendi') && e('本地项目');  //检查其中某个项目。
