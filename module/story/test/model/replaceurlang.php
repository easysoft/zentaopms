#!/usr/bin/env php
<?php

/**

title=测试 storyModel->replaceURLang();
timeout=0
cid=18580

- 查看更换语言之前的create属性create @提研发需求
- 查看更换语言之前的create属性create @提用户需求
- 查看更换语言之前的common属性common @研发需求
- 查看更换语言之前的common属性common @研发需求
- 查看更换语言之前的title属性title @研发需求名称
- 查看更换语言之前的title属性title @用户需求名称

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

global $tester;
$tester->loadModel('story');
$tester->story->lang->SRCommon = '研发需求';
$tester->story->lang->URCommon = '用户需求';
include($tester->story->app->basePath . 'module' . DS . 'common' . DS . 'lang' . DS . 'zh-cn.php');
include($tester->story->app->basePath . 'module' . DS . 'story' . DS . 'lang' . DS . 'zh-cn.php');
$lang->story->importCase = '导入研发需求';
$lang->story->num        = '需求记录数';

$beforeLang = clone $tester->story->lang->story;
$tester->story->replaceURLang('requirement');
$afterLang = clone $tester->story->lang->story;

r($beforeLang) && p('create') && e('提研发需求');  //查看更换语言之前的create
r($afterLang)  && p('create') && e('提用户需求');  //查看更换语言之前的create

r($beforeLang) && p('common') && e('研发需求');    //查看更换语言之前的common
r($afterLang)  && p('common') && e('研发需求');    //查看更换语言之前的common

r($beforeLang) && p('title') && e('研发需求名称'); //查看更换语言之前的title
r($afterLang)  && p('title') && e('用户需求名称'); //查看更换语言之前的title