#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

$userquery = zdTable('userquery');
$userquery->sql->range('(1 AND `plan` = 40)');
$userquery->form->range('``');
$userquery->gen(1);

/**

title=测试 storyModel->setSearchSessionByQueryID();
cid=1
pid=1

*/

global $tester;
$storyModel = $tester->loadModel('story');
unset($_SESSION['storyQuery']);
$storyModel->setSearchSessionByQueryID(0, 'storyQuery', 'storyForm');
r($_SESSION['storyQuery']) && p() && e(' 1 = 1'); //不传入数据。
unset($_SESSION['storyQuery']);
$storyModel->setSearchSessionByQueryID(1, 'storyQuery', 'storyForm');
r($_SESSION['storyQuery']) && p() && e('1 AND `plan` = 40'); //传入有数据的queryID。
unset($_SESSION['storyQuery']);
$storyModel->setSearchSessionByQueryID(8, 'storyQuery', 'storyForm');
r($_SESSION['storyQuery']) && p() && e(' 1 = 1'); //传入无数据的queryID。
