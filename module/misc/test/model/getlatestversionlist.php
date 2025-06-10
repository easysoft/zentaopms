#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 miscModel->getLatestVersionList();
timeout=0
cid=1

- 执行miscModel模块的getLatestVersionList方法，参数是null  @0

*/
global $tester, $config;
$miscModel = $tester->loadModel('misc');

$_SERVER['HTTP_REFERER'] = 'https://www.zentao.net';
r($miscModel->getLatestVersionList(null)) && p() && e('0');
