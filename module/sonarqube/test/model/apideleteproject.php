#!/usr/bin/env php
<?php

/**

title=测试 sonarqubeModel::apiDeleteProject();
cid=0

- 通过不正确的sonarqubeID、projectKey,删除SonarQube项目 @return false
- 正确的sonarqubeID,空的projectKey,删除SonarQube项目第0条的msg属性 @The 'project' parameter is missing
- 正确的sonarqubeID,不存在的projectKey,删除SonarQube项目第0条的msg属性 @Project 'no_project' not found
- 正确的sonarqubeID、projectKey,删除SonarQube项目 @return true

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/sonarqube.class.php';
su('admin');

zdTable('pipeline')->config('pipeline')->gen(5);

$sonarqubeID = 2;

$t_empty_projectkey = '';
$t_no_projectkey    = 'no_project';
$t_projectkey       = 'unittest17';
$t_projectName      = 'unit_test17';

$sonarqube = new sonarqubeTest();
r($sonarqube->apiDeleteProjectTest(0, $t_empty_projectkey))            && p()        && e('return false');                       //通过不正确的sonarqubeID、projectKey,删除SonarQube项目
r($sonarqube->apiDeleteProjectTest($sonarqubeID, $t_empty_projectkey)) && p('0:msg') && e("The 'project' parameter is missing"); //正确的sonarqubeID,空的projectKey,删除SonarQube项目
r($sonarqube->apiDeleteProjectTest($sonarqubeID, $t_no_projectkey))    && p('0:msg') && e("Project 'no_project' not found");     //正确的sonarqubeID,不存在的projectKey,删除SonarQube项目
$sonarqube->apiCreateProjectTest($sonarqubeID, array('projectName' => $t_projectkey, 'projectKey' => $t_projectkey));
r($sonarqube->apiDeleteProjectTest($sonarqubeID, $t_projectkey))       && p()        && e("return true");                        //正确的sonarqubeID、projectKey,删除SonarQube项目
