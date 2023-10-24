#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 storyModel->activate();
cid=1
pid=1

查看激活之前的需求状态 >> active
查看激活之前的需求状态 >> closed
查看激活之前的需求状态 >> changed
查看激活之前的需求状态 >> draft
查看激活之后的需求状态 >> active
查看激活之后的需求状态 >> active
查看激活之后的需求状态 >> active
查看激活之后的需求状态 >> active

*/

global $tester;
$tester->loadModel('story');

$beforeActivate2   = $tester->story->getById(2);
$beforeActivate63   = $tester->story->getById(63);
$beforeActivate100 = $tester->story->getById(100);
$beforeActivate301 = $tester->story->getById(301);

$_POST['status'] = 'active';
$tester->story->activate(2);
$tester->story->activate(63);
$tester->story->activate(100);
$tester->story->activate(301);

$afterActivate2   = $tester->story->getById(2);
$afterActivate63  = $tester->story->getById(63);
$afterActivate100 = $tester->story->getById(100);
$afterActivate301 = $tester->story->getById(301);

r($beforeActivate2)   && p('status') && e('active');  //查看激活之前的需求状态
r($beforeActivate63)  && p('status') && e('closed');  //查看激活之前的需求状态
r($beforeActivate100) && p('status') && e('changed'); //查看激活之前的需求状态
r($beforeActivate301) && p('status') && e('draft');   //查看激活之前的需求状态
r($afterActivate2)    && p('status') && e('active');  //查看激活之后的需求状态
r($afterActivate63)   && p('status') && e('active');  //查看激活之后的需求状态
r($afterActivate100)  && p('status') && e('active');  //查看激活之后的需求状态
r($afterActivate301)  && p('status') && e('active');  //查看激活之后的需求状态

