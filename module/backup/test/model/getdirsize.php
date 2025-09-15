#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 backupModel::getDirSize();
timeout=0
cid=0

- 执行backupModel模块的getDirSize方法，参数是'/nonexistent/dir/path'  @0
- 执行backupModel模块的getDirSize方法，参数是''  @0
- 执行backupModel模块的getDirSize方法，参数是__FILE__  @0
- 执行backupModel模块的getDirSize方法，参数是dirname  @~~
- 执行backupModel模块的getDirSize方法，参数是null  @0

*/

global $tester;
$backupModel = $tester->loadModel('backup');

r($backupModel->getDirSize('/nonexistent/dir/path')) && p() && e(0);
r($backupModel->getDirSize('')) && p() && e(0);
r($backupModel->getDirSize(__FILE__)) && p() && e(0);
r($backupModel->getDirSize(dirname(__FILE__, 5) . '/test')) && p() && e('~~');
r($backupModel->getDirSize(null)) && p() && e(0);