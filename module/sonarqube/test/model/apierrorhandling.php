#!/usr/bin/env php
<?php

/**

title=测试 sonarqubeModel::apiErrorHandling();
timeout=0
cid=18375

- 执行sonarqubeTest模块的apiErrorHandlingTest方法，参数是null  @0
- 执行sonarqubeTest模块的apiErrorHandlingTest方法，参数是$responseWithSingleError  @0
- 执行sonarqubeTest模块的apiErrorHandlingTest方法，参数是$responseWithMultipleErrors  @0
- 执行sonarqubeTest模块的apiErrorHandlingTest方法，参数是$responseWithoutErrors  @0
- 执行sonarqubeTest模块的apiErrorHandlingTest方法，参数是$responseWithEmptyErrors  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/sonarqube.unittest.class.php';

su('admin');

$sonarqubeTest = new sonarqubeTest();

r($sonarqubeTest->apiErrorHandlingTest(null)) && p() && e('0');

$responseWithSingleError = new stdclass();
$responseWithSingleError->errors = array();
$responseWithSingleError->errors[0] = new stdclass();
$responseWithSingleError->errors[0]->msg = '单个错误信息';

r($sonarqubeTest->apiErrorHandlingTest($responseWithSingleError)) && p() && e('0');

$responseWithMultipleErrors = new stdclass();
$responseWithMultipleErrors->errors = array();
$responseWithMultipleErrors->errors[0] = new stdclass();
$responseWithMultipleErrors->errors[0]->msg = '错误信息1';
$responseWithMultipleErrors->errors[1] = new stdclass();
$responseWithMultipleErrors->errors[1]->msg = '错误信息2';

r($sonarqubeTest->apiErrorHandlingTest($responseWithMultipleErrors)) && p() && e('0');

$responseWithoutErrors = new stdclass();
$responseWithoutErrors->data = '没有errors属性';

r($sonarqubeTest->apiErrorHandlingTest($responseWithoutErrors)) && p() && e('0');

$responseWithEmptyErrors = new stdclass();
$responseWithEmptyErrors->errors = array();

r($sonarqubeTest->apiErrorHandlingTest($responseWithEmptyErrors)) && p() && e('0');