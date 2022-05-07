#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tutorial.class.php';
su('admin');

/**

title=测试 tutorialModel->getExecutionStoryPairs();
cid=1
pid=1

*/

$tutorial = new tutorialTest();

r($tutorial->getExecutionStoryPairsTest()) && p('1') && e('Test story');
