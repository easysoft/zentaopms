#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tutorial.class.php';
su('admin');

/**

title=测试 tutorialModel->getExecutionStories();
cid=1
pid=1

检查是否获取到数据 >> wait

*/

$tutorial = new tutorialTest();

r($tutorial->getExecutionStoriesTest()) && p('1:stage') && e('wait'); //检查是否获取到数据