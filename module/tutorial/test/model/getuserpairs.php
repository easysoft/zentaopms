#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tutorial.class.php';
su('admin');

/**

title=测试 tutorialModel->getUserPairs();
cid=1
pid=1

测试是否能拿到数据 >> admin

*/

$tutorial = new tutorialTest();

r($tutorial->getUserPairsTest()) && p('admin') && e('admin'); //测试是否能拿到数据