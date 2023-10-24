#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->setStage();
cid=1
pid=1

为需求2拆分几个任务. >> execution
把需求2下面的任务都完成. >> story
查看设置之后的需求状态 >> projected
查看设置之后的需求状态 >> testing
查看设置之后的需求状态 >> tested

*/

$story = new storyTest();

$before = $tester->story->getById(2);

/* 为需求2拆分几个任务. */
$tester->dao->update(TABLE_TASK)->set('story')->eq(2)->where('execution')->eq(101)->exec();
$tester->story->setStage(2);

$after1 = $tester->story->getById(2);

/* 把需求2下面的任务都完成. */
$tester->dao->update(TABLE_TASK)->set('status')->eq('done')->where('story')->eq(2)->exec();
$tester->story->setStage(2);

$after2 = $tester->story->getById(2);

r($before)  && p('stage') && e('projected'); //查看设置之后的需求状态
r($after1)  && p('stage') && e('testing');   //查看设置之后的需求状态
r($after2)  && p('stage') && e('tested');    //查看设置之后的需求状态
