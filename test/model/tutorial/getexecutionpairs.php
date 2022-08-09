#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tutorial.class.php';
su('admin');

/**

title=测试 tutorialModel->getExecutionPairs();
cid=1
pid=1

检查获取的数据信息 >> Test execution

*/

$tutorial = new tutorialTest();

r($tutorial->getExecutionPairsTest()) && p('3') && e('Test execution'); //检查获取的数据信息