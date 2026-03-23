#!/usr/bin/env php
<?php
/**

title=测试 pivotZen::show();
timeout=0
cid=0

- 透视表名称属性pivotName @完成项目工时透视表
- 透视表位置属性currentMenu @85_1001
- 透视表ID第pivot条的id属性 @1001
- 透视表权限第pivot条的acl属性 @open
- 透视表模式第pivot条的mode属性 @builder
- 透视表驱动第pivot条的driver属性 @mysql

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivotzen.unittest.class.php';

su('admin');

global $tester, $app;
$appPath = $app->getAppRoot();
$sqlFile = $appPath . 'test/data/pivot.sql';
$tester->dbh->exec(file_get_contents($sqlFile));

$pivotTest = new pivotZenTest();

$result = $pivotTest->showTest(85, 1001);
r($result) && p('pivotName')    && e('完成项目工时透视表'); //透视表名称
r($result) && p('currentMenu')  && e('85_1001');            //透视表位置
r($result) && p('pivot:id')     && e('1001');               //透视表ID
r($result) && p('pivot:acl')    && e('open');               //透视表权限
r($result) && p('pivot:mode')   && e('builder');            //透视表模式
r($result) && p('pivot:driver') && e('mysql');              //透视表驱动
