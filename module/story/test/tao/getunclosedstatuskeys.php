#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getUnclosedStatusKeys();
cid=0

- 执行storyModel模块的getUnclosedStatusKeys方法  @|draft|reviewing|active|changing

*/
include dirname(__FILE__, 5) . "/test/lib/init.php";

global $tester;
$storyModel = $tester->loadModel('story');

r(implode('|', $storyModel->getUnclosedStatusKeys())) && p() && e('|draft|reviewing|active|changing');
