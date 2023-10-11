#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('story')->gen(20);

/**

title=测试 storyModel->get2BeClosed();
cid=1
pid=1

获取产品3下需要关闭的软件需求数量 >> 1
获取产品3下需要关闭的软件需求详情 >> 软件需求10,story,developed
获取产品10下需要关闭的软件需求数量 >> 1
获取产品10下需要关闭的软件需求详情 >> 软件需求38,story,released

*/

global $tester;
$tester->loadModel('story');
$toBeClosed1 = $tester->story->get2BeClosed(3, 0, array(), 'story', 'id_desc');

r(count($toBeClosed1)) && p()                      && e('1');                          //获取产品3下需要关闭的软件需求数量
r($toBeClosed1)        && p('10:title,type,stage') && e('软件需求10,story,developed'); //获取产品3下需要关闭的软件需求详情
