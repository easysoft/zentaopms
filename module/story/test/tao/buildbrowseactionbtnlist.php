#!/usr/bin/env php
<?php

/**

title=测试 storyModel->buildBrowseActionBtnList();
cid=0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('story')->gen(10);
zdTable('project')->gen(20);

global $tester;
$tester->loadModel('story');
$tester->story->lang->SRCommon      = '研发需求';
$tester->story->config->requestType = 'PATH_INFO';
$tester->story->config->webRoot     = '/';

include($tester->story->app->basePath . 'module' . DS . 'story' . DS . 'lang' . DS . 'zh-cn.php');
include($tester->story->app->basePath . 'module' . DS . 'story' . DS . 'control.php');
include($tester->story->app->basePath . 'module' . DS . 'product' . DS . 'control.php');
$tester->story->app->user->admin = true;
$tester->story->app->moduleName  = 'product';
$tester->story->app->methodName  = 'browse';
$tester->story->app->control     = new story();
$requirement = $tester->story->fetchById(1);
$story       = $tester->story->fetchById(2);
$execution   = $tester->story->loadModel('project')->fetchById(11);

$requirementOperateMenu1 = $tester->story->buildBrowseActionBtnList($requirement, "storyID=1", 'requirement');
$storyOperateMenu1       = $tester->story->buildBrowseActionBtnList($story, "storyID=2", 'story');

$execution->multiple = 0;
$tester->story->app->tab = 'execution';
$storyOperateMenu2 = $tester->story->buildBrowseActionBtnList($story, "storyID=2", 'story', $execution);

r($requirementOperateMenu1) && p('0:name,hint,disabled')   && e('change,只有激活状态的研发需求，才能进行变更,1');             //查看用户需求列表操作菜单第一个链接
r($requirementOperateMenu1) && p('1:name,data-toggle,url') && e('submitreview,modal,/story-submitReview-1-requirement.html'); //查看用户需求列表操作菜单第二个链接
r($requirementOperateMenu1) && p('3:name,data-toggle,url') && e('close,modal,/story-close-1--requirement.html');              //查看用户需求列表操作菜单第四个链接
r($requirementOperateMenu1) && p('5:name,url')             && e('edit,/story-edit-1-default-requirement.html');               //查看用户需求列表操作菜单第六个链接

r($storyOperateMenu1)       && p('0:name,hint,url')         && e('change,变更,/story-change-2--story.html');                  //查看软件需求详情操作菜单第一个链接
r($storyOperateMenu1)       && p('1:name,hint,disabled')    && e('review,该研发需求已是激活状态，无需评审,1');                //查看软件需求详情操作菜单第二个链接
r($storyOperateMenu1)       && p('3:name,data-toggle,url')  && e('close,modal,/story-close-2--story.html');                   //查看软件需求详情操作菜单第四个链接
r($storyOperateMenu1)       && p('5:name,url')              && e('edit,/story-edit-2-default-story.html');                    //查看软件需求详情操作菜单第六个链接
r($storyOperateMenu1)       && p('6:name,url')              && e('testcase,/testcase-create-1-0-0--0-2.html');                //查看软件需求详情操作菜单第七个链接

r($storyOperateMenu2)       && p('8:name,className,url') && e('unlink,ajax-submit,/execution-unlinkStory-11-2-yes.html');     //查看软件需求详情操作菜单第九个链接
