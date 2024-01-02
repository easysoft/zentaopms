#!/usr/bin/env php
<?php

/**

title=测试 sonarqubeModel::isClickable();
cid=0

- 页面模块是 repo， sonarqube 中 exec 字段有值，检查 execJob 方法的结果。 @0
- 页面模块是 repo， sonarqube 中 exec 字段无值，检查 execJob 方法的结果。 @1
- 页面模块不是 repo， sonarqube 中 jobID 字段有值，检查 execJob 方法的结果。 @1
- 页面模块不是 repo， sonarqube 中 jobID 字段无值，检查 execJob 方法的结果。 @0
- 页面模块是 repo， sonarqube 中 report 字段有值，检查 reportView 方法的结果。 @0
- 页面模块是 repo， sonarqube 中 report 字段有值，检查 reportView 方法的结果。 @1
- 页面模块不是 repo， sonarqube 中 reportView 字段有值，检查 reportView 方法的结果。 @1
- 页面模块不是 repo， sonarqube 中 reportView 字段有值，检查 reportView 方法的结果。 @0
- 页面模块不是 repo， 检查 edit 方法的结果。 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

global $tester;
$tester->loadModel('sonarqube');
$tester->sonarqube->app->rawModule = 'repo';

$action    = 'execJob';
$sonarqube = new stdclass();
$sonarqube->exec  = 1;
$sonarqube->jobID = 1;

r(sonarqubeModel::isClickable($sonarqube, $action)) && p() && e('0'); // 页面模块是 repo， sonarqube 中 exec 字段有值，检查 execJob 方法的结果。

$sonarqube->exec  = 0;
r(sonarqubeModel::isClickable($sonarqube, $action)) && p() && e('1'); // 页面模块是 repo， sonarqube 中 exec 字段无值，检查 execJob 方法的结果。

$tester->sonarqube->app->rawModule = 'sonarqube';
r(sonarqubeModel::isClickable($sonarqube, $action)) && p() && e('1'); // 页面模块不是 repo， sonarqube 中 jobID 字段有值，检查 execJob 方法的结果。

$sonarqube->jobID = 0;
r(sonarqubeModel::isClickable($sonarqube, $action)) && p() && e('0'); // 页面模块不是 repo， sonarqube 中 jobID 字段无值，检查 execJob 方法的结果。

$action = 'reportView';
$tester->sonarqube->app->rawModule = 'repo';
$sonarqube->report     = 1;
$sonarqube->reportView = 1;
r(sonarqubeModel::isClickable($sonarqube, $action)) && p() && e('0'); // 页面模块是 repo， sonarqube 中 report 字段有值，检查 reportView 方法的结果。

$sonarqube->report = 0;
r(sonarqubeModel::isClickable($sonarqube, $action)) && p() && e('1'); // 页面模块是 repo， sonarqube 中 report 字段有值，检查 reportView 方法的结果。

$tester->sonarqube->app->rawModule = 'sonarqube';
r(sonarqubeModel::isClickable($sonarqube, $action)) && p() && e('1'); // 页面模块不是 repo， sonarqube 中 reportView 字段有值，检查 reportView 方法的结果。

$sonarqube->reportView = 0;
r(sonarqubeModel::isClickable($sonarqube, $action)) && p() && e('0'); // 页面模块不是 repo， sonarqube 中 reportView 字段有值，检查 reportView 方法的结果。

r(sonarqubeModel::isClickable($sonarqube, 'edit')) && p() && e('1'); // 页面模块不是 repo， 检查 edit 方法的结果。
