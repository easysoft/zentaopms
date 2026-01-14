#!/usr/bin/env php
<?php

/**

title=测试 sonarqubeModel::getApiBase();
timeout=0
cid=18383

- 测试步骤1：有效sonarqubeID获取API基础信息 @https://sonar.example.com/api/%s
- 测试步骤2：不存在的sonarqubeID获取API基础信息 @return empty
- 测试步骤3：负数sonarqubeID获取API基础信息 @return empty
- 测试步骤4：零值sonarqubeID获取API基础信息 @return empty
- 测试步骤5：字符串类型sonarqubeID获取API基础信息 @return empty

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$table = zenData('pipeline');
$table->id->range('1-10');
$table->type->range('sonarqube{5},jenkins{3},gitlab{2}');
$table->name->range('SonarQube1,SonarQube2,SonarQube3,Jenkins1,Jenkins2');
$table->url->range('https://sonardev.qc.oop.cc,https://sonar.example.com,http://localhost:9000');
$table->token->range('admin:password123,user:token456,test:secret789');
$table->deleted->range('0{8},1{2}');
$table->gen(10);

$sonarqube = new sonarqubeModelTest();
r($sonarqube->getApiBaseTest(2))     && p() && e('https://sonar.example.com/api/%s'); // 测试步骤1：有效sonarqubeID获取API基础信息
r($sonarqube->getApiBaseTest(999))   && p() && e('return empty');                      // 测试步骤2：不存在的sonarqubeID获取API基础信息
r($sonarqube->getApiBaseTest(-1))    && p() && e('return empty');                      // 测试步骤3：负数sonarqubeID获取API基础信息
r($sonarqube->getApiBaseTest(0))     && p() && e('return empty');                      // 测试步骤4：零值sonarqubeID获取API基础信息
r($sonarqube->getApiBaseTest('abc')) && p() && e('return empty');                      // 测试步骤5：字符串类型sonarqubeID获取API基础信息