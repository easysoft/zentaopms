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

r($requirementOperateMenu1['mainMenu'])           && p('0:icon,data-toggle,url') && e('confirm,modal,/story-submitReview-1-requirement.html');         //查看用户需求详情操作菜单mainMenu的第一个链接
r($requirementOperateMenu1['mainMenu'])           && p('1:icon,data-toggle,url') && e('hand-right,modal,/story-assignTo-1-default--requirement.html'); //查看用户需求详情操作菜单mainMenu的第二个链接
r($requirementOperateMenu1['mainMenu'])           && p('2:icon,data-toggle,url') && e('off,modal,/story-close-1--requirement.html');                   //查看用户需求详情操作菜单mainMenu的第三个链接
r($requirementOperateMenu1['suffixMenu'])         && p('0:icon,url')             && e('edit,/story-edit-1-default-requirement.html');                  //查看用户需求详情操作菜单suffixMenu的第一个链接
r($requirementOperateMenu1['suffixMenu'])         && p('1:icon,url')             && e('copy,/story-create-1-0-1821-1-0-0-0-0--requirement.html');      //查看用户需求详情操作菜单suffixMenu的第二个链接
r($requirementOperateMenu1['suffixMenu'])         && p('2:icon,url,class')       && e('trash,/story-delete-1.html,ajax-submit');                       //查看用户需求详情操作菜单suffixMenu的第三个链接
r($storyOperateMenu1['mainMenu'])                 && p('0:icon,url')             && e('alter,/story-change-2.html');                                   //查看用户需求详情操作菜单mainMenu的第一个链接
r($storyOperateMenu1['mainMenu'])                 && p('1:icon,data-toggle,url') && e('hand-right,modal,/story-assignTo-2-default--story.html');       //查看软件需求详情操作菜单mainMenu的第二个链接
r($storyOperateMenu1['mainMenu'])                 && p('2:icon,data-toggle,url') && e('off,modal,/story-close-2--story.html');                         //查看软件需求详情操作菜单mainMenu的第三个链接
r($storyOperateMenu1['mainMenu'])                 && p('3:data-toggle,url')      && e('dropdown,#caseActions');                                        //查看软件需求详情操作菜单mainMenu的第四个链接
r($storyOperateMenu1['suffixMenu'])               && p('0:icon,url')             && e('edit,/story-edit-2-default-story.html');                        //查看软件需求详情操作菜单suffixMenu的第一个链接
r($storyOperateMenu1['suffixMenu'])               && p('1:icon,url')             && e('copy,/story-create-1-0-1822-2-0-0-0-0--story.html');            //查看软件需求详情操作菜单suffixMenu的第二个链接
r($storyOperateMenu1['suffixMenu'])               && p('2:icon,url,class')       && e('trash,/story-delete-2.html,ajax-submit');                       //查看软件需求详情操作菜单suffixMenu的第三个链接
r($storyOperateMenu1['dropMenus']['caseActions']) && p('0:data-toggle,url')      && e('modal,/testcase-create-1-0-0--0-2.html');                       //查看软件需求详情操作菜单dropMenus的第一个链接
r($storyOperateMenu1['dropMenus']['caseActions']) && p('1:data-toggle,url')      && e('modal,/testcase-batchCreate-1-0-0-2.html');                     //查看软件需求详情操作菜单dropMenus的第二个链接

r($storyOperateMenu2['mainMenu']) && p('4:icon,url') && e('plus,/task-create--2-1822.html');  //查看软件需求详情操作菜单mainMenu的第五个链接
