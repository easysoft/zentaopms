#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=instanceModel->dbListToOptions();
timeout=0
cid=1

- 查看转换后的options数量 @2
- 查看转换后的options属性mysql @mysql-server
- 查看转换后的options属性sqllite @sqllite-server

*/

global $tester;
$tester->loadModel('instance');

$dbList = array();
$dbList[1] = new stdClass();
$dbList[1]->name  = 'mysql';
$dbList[1]->alias = 'mysql-server';

$dbList[2] = new stdClass();
$dbList[2]->name  = 'sqllite';
$dbList[2]->alias = 'sqllite-server';

$options = $tester->instance->dbListToOptions($dbList);

r(count($options)) && p()          && e('2');              // 查看转换后的options数量
r($options)        && p('mysql')   && e('mysql-server');   // 查看转换后的options
r($options)        && p('sqllite') && e('sqllite-server'); // 查看转换后的options