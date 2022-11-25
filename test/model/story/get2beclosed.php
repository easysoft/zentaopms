#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

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
$toBeClosed2 = $tester->story->get2BeClosed(10, 0, array(), 'story', 'id_asc');

r(count($toBeClosed1)) && p()                      && e('1');                          //获取产品3下需要关闭的软件需求数量
r($toBeClosed1)        && p('10:title,type,stage') && e('软件需求10,story,developed'); //获取产品3下需要关闭的软件需求详情
r(count($toBeClosed2)) && p()                      && e('1');                          //获取产品10下需要关闭的软件需求数量
r($toBeClosed2)        && p('38:title,type,stage') && e('软件需求38,story,released');  //获取产品10下需要关闭的软件需求详情