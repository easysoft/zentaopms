#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/projectstory.class.php';
su('admin');

/**

title=测试 projectstoryModel->setMenu();
cid=1
pid=1

这里返回一个页面,项目外的情况 >> 您无权访问该产品

*/

$projectstory = new projectstoryTest('admin');

r($projectstory->setMenuTest(array(3, 83))) && p() && e('您无权访问该产品'); //这里返回一个页面,项目外的情况