#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tutorial.class.php';
su('admin');

/**

title=测试 tutorialModel->getStories();
cid=1
pid=1

测试是否能拿到数据 >> 1
测试是否能拿到数据 >> 3

*/

$tutorial = new tutorialTest();

r($tutorial->getStoriesTest()) && p('0:module') && e('1'); //测试是否能拿到数据
r($tutorial->getStoriesTest()) && p('1:pri')    && e('3'); //测试是否能拿到数据