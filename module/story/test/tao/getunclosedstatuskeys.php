#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";

/**

title=测试 storyModel->getUnclosedStatusKeys();
cid=1
pid=1

*/

global $tester;
$storyModel = $tester->loadModel('story');

r(implode('|', $storyModel->getUnclosedStatusKeys())) && p() && e('|draft|reviewing|active|changing');
