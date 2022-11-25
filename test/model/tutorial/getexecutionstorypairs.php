#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tutorial.class.php';
su('admin');

/**

title=测试 tutorialModel->getExecutionStoryPairs();
cid=1
pid=1

测试是否能拿到数据 >> Test story

*/

$tutorial = new tutorialTest();

r($tutorial->getExecutionStoryPairsTest()) && p('1') && e('Test story'); //测试是否能拿到数据