#!/usr/bin/env php
<?php

/**

title=测试 storyModel->buildOperateMenu();
cid=0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('story')->gen(10);
zdTable('project')->gen(20);

global $tester;
$tester->loadModel('story');
$tester->story->config->requestType = 'PATH_INFO';
$tester->story->config->webRoot     = '/';
$tester->story->app->user->admin    = true;
$tester->story->app->moduleName     = 'story';
$tester->story->app->methodName     = 'view';

include($tester->story->app->basePath . DS . 'module' . DS . 'story' . DS . 'control.php');
$tester->story->app->control     = new story();
$requirement = $tester->story->fetchById(1);
$story       = $tester->story->fetchById(2);
$execution   = $tester->story->loadModel('project')->fetchById(11);

$config->requestType = 'PATH_INFO';
$requirementOperateMenu1 = $tester->story->buildOperateMenu($requirement, 'view', null, 'requirement');
$storyOperateMenu1       = $tester->story->buildOperateMenu($story, 'view', null, 'story');

$execution->multiple = 0;
$tester->story->app->tab = 'execution';
$storyOperateMenu2 = $tester->story->buildOperateMenu($story, 'view', $execution, 'story');

r($requirementOperateMenu1) && p('0:icon,data-toggle,url') && e('confirm,modal,/story-submitReview-1-requirement.html');         // 查看用户需求详情操作菜单的第一个链接
r($requirementOperateMenu1) && p('1:icon,data-toggle,url') && e('hand-right,modal,/story-assignTo-1-default--requirement.html'); // 查看用户需求详情操作菜单的第二个链接
r($requirementOperateMenu1) && p('2:icon,data-toggle,url') && e('off,modal,/story-close-1--requirement.html');                   // 查看用户需求详情操作菜单的第三个链接
r($requirementOperateMenu1) && p('0:icon,url')             && e('confirm,/story-submitReview-1-requirement.html');               // 查看用户需求详情操作菜单的第一个链接
r($requirementOperateMenu1) && p('1:icon,url')             && e('hand-right,/story-assignTo-1-default--requirement.html');       // 查看用户需求详情操作菜单的第二个链接
r($requirementOperateMenu1) && p('2:icon,url')             && e('off,/story-close-1--requirement.html');                         // 查看用户需求详情操作菜单的第三个链接
r($storyOperateMenu1)       && p('0:icon,url')             && e('alter,/story-change-2.html');                                   // 查看用户需求详情操作菜单的第一个链接
r($storyOperateMenu1)       && p('1:icon,data-toggle,url') && e('hand-right,modal,/story-assignTo-2-default--story.html');       // 查看软件需求详情操作菜单的第二个链接
r($storyOperateMenu1)       && p('2:icon,data-toggle,url') && e('off,modal,/story-close-2--story.html');                         // 查看软件需求详情操作菜单的第三个链接
r($storyOperateMenu1)       && p('0:icon,url')             && e('alter,/story-change-2.html');                                   // 查看软件需求详情操作菜单的第一个链接
r($storyOperateMenu1)       && p('1:icon,url')             && e('hand-right,/story-assignTo-2-default--story.html');             // 查看软件需求详情操作菜单的第二个链接
r($storyOperateMenu1)       && p('2:icon,url')             && e('off,/story-close-2--story.html');                               // 查看软件需求详情操作菜单的第三个链接
r($storyOperateMenu2)       && p('0:icon,url')             && e('alter,/story-change-2.html');                                   // 查看软件需求详情操作菜单的第一个链接
