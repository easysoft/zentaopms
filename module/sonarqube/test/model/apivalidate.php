#!/usr/bin/env php
<?php

/**

title=测试 sonarqubeModel::apiValidate();
timeout=0
cid=18380

- 步骤1：使用默认有效配置验证 @success
- 步骤2：有效host但无效token @return false
- 步骤3：有效host但非管理员token @return false
- 步骤4：空host参数 @return false
- 步骤5：无效格式host @return false
- 步骤6：空token参数 @return false
- 步骤7：特殊字符host @return false

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('pipeline')->loadYaml('pipeline')->gen(5);

$sonarqubeID = 2;

$validHost      = 'https://sonardev.qc.oop.cc';
$validToken     = 'c29uYXI6a2tHQXpFMXA5cE9vcUpBOWJMSWg=';
$invalidToken   = 'abc';
$noAdminToken   = 'dGVzdDoxMjM0NTY=';
$emptyHost      = '';
$invalidHost    = 'not-a-valid-url';
$emptyToken     = '';
$specialCharHost = 'https://test<script>alert(1)</script>.com';

$sonarqube = new sonarqubeModelTest();
r($sonarqube->apiValidateTest())                                        && p() && e('success');      // 步骤1：使用默认有效配置验证
r($sonarqube->apiValidateTest($validHost, $invalidToken, false))        && p() && e('return false'); // 步骤2：有效host但无效token
r($sonarqube->apiValidateTest($validHost, $noAdminToken, false))        && p() && e('return false'); // 步骤3：有效host但非管理员token
r($sonarqube->apiValidateTest($emptyHost, $validToken, false))          && p() && e('return false'); // 步骤4：空host参数
r($sonarqube->apiValidateTest($invalidHost, $validToken, false))        && p() && e('return false'); // 步骤5：无效格式host
r($sonarqube->apiValidateTest($validHost, $emptyToken, false))          && p() && e('return false'); // 步骤6：空token参数
r($sonarqube->apiValidateTest($specialCharHost, $validToken, false))    && p() && e('return false'); // 步骤7：特殊字符host