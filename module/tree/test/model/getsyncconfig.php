#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tree.unittest.class.php';
su('admin');

/**

title=测试 treeModel->getSyncConfig();
timeout=0
cid=19380

- 不传入任何数据，测试配置。 @0
- 测试 feedback 配置。属性sync @1
- 测试 ticket 配置。属性sync @1
- 测试 task 配置。属性sync @0
- 测试 bug 配置。属性sync @0

*/

global $tester;
$treeModel = $tester->loadModel('tree');

$treeModel->config->global->syncProduct = json_encode(array('feedback' => array('sync' => 1), 'ticket' => array('sync' => 1)));

r($treeModel->getSyncConfig())           && p()       && e("0");  // 不传入任何数据，测试配置。
r($treeModel->getSyncConfig('feedback')) && p('sync') && e("1");  // 测试 feedback 配置。
r($treeModel->getSyncConfig('ticket'))   && p('sync') && e("1");  // 测试 ticket 配置。
r($treeModel->getSyncConfig('task'))     && p('sync') && e("0");  // 测试 task 配置。
r($treeModel->getSyncConfig('bug'))      && p('sync') && e("0");  // 测试 bug 配置。