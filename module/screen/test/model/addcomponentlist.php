#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

/**

title=测试 screenModel->addComponentList();
timeout=0
cid=1

*/

global $app;
$screen = new screenTest();
$scheme = file_get_contents($app->moduleRoot . 'screen/json/screen.json');
$scheme = json_decode($scheme);
