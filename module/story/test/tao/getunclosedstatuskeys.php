#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getUnclosedStatusKeys();
cid=18649

- 获取草稿状态属性1 @draft
- 获取评审中状态属性2 @reviewing
- 获取激活状态属性3 @active
- 获取变更中状态属性4 @changing
- 获取空值 @~~

*/
include dirname(__FILE__, 5) . "/test/lib/init.php";

global $tester;
$storyModel = $tester->loadModel('story');

r($storyModel->getUnclosedStatusKeys()) && p('1') && e('draft');     //获取草稿状态
r($storyModel->getUnclosedStatusKeys()) && p('2') && e('reviewing'); //获取评审中状态
r($storyModel->getUnclosedStatusKeys()) && p('3') && e('active');    //获取激活状态
r($storyModel->getUnclosedStatusKeys()) && p('4') && e('changing');  //获取变更中状态
r($storyModel->getUnclosedStatusKeys()) && p('0') && e('~~');        //获取空值
