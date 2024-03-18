#!/usr/bin/env php
<?php

/**

title=测试 storyModel->get2BeClosed();
cid=0

- 获取产品3下需要关闭的软件需求数量 @1
- 获取产品3下需要关闭的软件需求详情
 - 第10条的title属性 @软件需求10
 - 第10条的type属性 @story
 - 第10条的stage属性 @developed

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('story')->gen(20);

global $tester;
$tester->loadModel('story');
$toBeClosed1 = $tester->story->get2BeClosed(3, 0, array(), 'story', 'id_desc');

r(count($toBeClosed1)) && p()                      && e('1');                          //获取产品3下需要关闭的软件需求数量
r($toBeClosed1)        && p('10:title,type,stage') && e('软件需求10,story,developed'); //获取产品3下需要关闭的软件需求详情
