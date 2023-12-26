#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 storyModel->replaceURLang();
cid=1
pid=1

*/

global $tester;
$tester->loadModel('story');

$beforeLang = clone $tester->story->lang->story;
$tester->story->replaceURLang('requirement');
$afterLang = clone $tester->story->lang->story;

r($beforeLang) && p('create') && e('提研发需求');  //查看更换语言之前的create
r($afterLang)  && p('create') && e('提用户需求');  //查看更换语言之前的create

r($beforeLang) && p('common') && e('研发需求');    //查看更换语言之前的common
r($afterLang)  && p('common') && e('用户需求');    //查看更换语言之前的common

r($beforeLang) && p('title') && e('研发需求名称'); //查看更换语言之前的title
r($afterLang)  && p('title') && e('用户需求名称'); //查看更换语言之前的title
