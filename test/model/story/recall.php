#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 storyModel->recall();
cid=1
pid=1

撤回评审之前的状态信息 >> 2,用户需求版本一62,active
撤回评审之后的状态信息 >> 2,用户需求版本一62,draft

*/

global $tester;

$beforeRecall = $tester->loadModel('story')->getByID(2);
$tester->loadModel('story')->recall(2);
$afterRecall  = $tester->story->getByID(2);

r($beforeRecall) && p('id,title,status') && e('2,用户需求版本一62,active'); // 撤回评审之前的状态信息
r($afterRecall)  && p('id,title,status') && e('2,用户需求版本一62,draft');  // 撤回评审之后的状态信息
