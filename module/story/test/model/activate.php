#!/usr/bin/env php
<?php
/**

title=测试 storyModel->activate();
cid=1
pid=1

- 查看激活之前的需求状态属性status @draft
- 查看激活之前的需求状态属性status @active
- 查看激活之前的需求状态属性status @closed
- 查看激活之前的需求状态属性status @changing
- 查看激活之后的需求状态属性status @active
- 查看激活之后的需求状态属性status @active
- 查看激活之后的需求状态属性status @active
- 查看激活之后的需求状态属性status @active

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('story')->gen(10);
zdTable('storyreview')->gen(0);
zdTable('action')->gen(0);
zdTable('storyspec')->gen(30);

su('admin');

global $tester;
$tester->loadModel('story');

$beforeActivate1 = $tester->story->getById(1);
$beforeActivate2 = $tester->story->getById(2);
$beforeActivate3 = $tester->story->getById(3);
$beforeActivate4 = $tester->story->getById(4);

$_POST['status'] = 'active';
$tester->story->activate(1, (object)$_POST);
$tester->story->activate(2, (object)$_POST);
$tester->story->activate(3, (object)$_POST);
$tester->story->activate(4, (object)$_POST);

$afterActivate1 = $tester->story->getById(1);
$afterActivate2 = $tester->story->getById(2);
$afterActivate3 = $tester->story->getById(3);
$afterActivate4 = $tester->story->getById(4);

r($beforeActivate1) && p('status') && e('draft');    //查看激活之前的需求状态
r($beforeActivate2) && p('status') && e('active');   //查看激活之前的需求状态
r($beforeActivate3) && p('status') && e('closed');   //查看激活之前的需求状态
r($beforeActivate4) && p('status') && e('changing'); //查看激活之前的需求状态

r($afterActivate1)  && p('status') && e('active');  //查看激活之后的需求状态
r($afterActivate2)  && p('status') && e('active');  //查看激活之后的需求状态
r($afterActivate3)  && p('status') && e('active');  //查看激活之后的需求状态
r($afterActivate4)  && p('status') && e('active');  //查看激活之后的需求状态
