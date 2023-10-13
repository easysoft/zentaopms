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

r($beforeLang) && p('create') && e('提软需');  //查看更换语言之前的create
r($afterLang)  && p('create') && e('提用需');  //查看更换语言之前的create

r($beforeLang) && p('common') && e('软需');    //查看更换语言之前的common
r($afterLang)  && p('common') && e('用需');    //查看更换语言之前的common

r($beforeLang) && p('title') && e('软需名称'); //查看更换语言之前的title
r($afterLang)  && p('title') && e('用需名称'); //查看更换语言之前的title
