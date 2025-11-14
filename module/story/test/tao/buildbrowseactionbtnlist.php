#!/usr/bin/env php
<?php

/**

title=测试 storyModel->buildBrowseActionBtnList();
timeout=0
cid=18600

- 查看用户需求列表操作菜单第一个链接
 - 第0条的name属性 @change
 - 第0条的hint属性 @只有激活状态的需求，才能进行变更
 - 第0条的disabled属性 @1
- 查看用户需求列表操作菜单第二个链接
 - 第1条的name属性 @submitreview
 - 第1条的data-toggle属性 @modal
 - 第1条的url属性 @/requirement-submitReview-1.html
- 查看用户需求列表操作菜单第四个链接
 - 第3条的name属性 @close
 - 第3条的data-toggle属性 @modal
 - 第3条的url属性 @/requirement-close-1-.html
- 查看用户需求列表操作菜单第六个链接
 - 第5条的name属性 @edit
 - 第5条的url属性 @/requirement-edit-1-default.html
- 查看软件需求详情操作菜单第一个链接
 - 第0条的name属性 @change
 - 第0条的hint属性 @变更
 - 第0条的url属性 @/story-change-2-.html
- 查看软件需求详情操作菜单第二个链接
 - 第1条的name属性 @review
 - 第1条的hint属性 @该需求已是激活状态，无需评审
 - 第1条的disabled属性 @1
- 查看软件需求详情操作菜单第四个链接
 - 第3条的name属性 @close
 - 第3条的data-toggle属性 @modal
 - 第3条的url属性 @/story-close-2-.html
- 查看软件需求详情操作菜单第六个链接
 - 第5条的name属性 @edit
 - 第5条的url属性 @/story-edit-2-default.html
- 查看软件需求详情操作菜单第七个链接
 - 第6条的name属性 @testcase
 - 第6条的url属性 @/testcase-create-1-0-0--0-2.html
- 查看软件需求详情操作菜单第九个链接
 - 第8条的name属性 @batchCreate
 - 第8条的className属性 @~~
 - 第8条的url属性 @~~

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('product')->gen(1);
zenData('story')->gen(10);
zenData('project')->gen(20);
zenData('storygrade')->gen(10);

global $tester;
$tester->loadModel('story');
$tester->story->lang->SRCommon      = '研发需求';
$tester->story->lang->URCommon      = '用户需求';
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

$maxGradeGroup           = $tester->story->getMaxGradeGroup();
$requirementOperateMenu1 = $tester->story->buildBrowseActionBtnList($requirement, "storyID=1", 'requirement', null, $maxGradeGroup);
$storyOperateMenu1       = $tester->story->buildBrowseActionBtnList($story, "storyID=2", 'story', null, $maxGradeGroup);

$execution->multiple = 0;
$tester->story->app->tab = 'execution';
$storyOperateMenu2 = $tester->story->buildBrowseActionBtnList($story, "storyID=2", 'story', $execution, $maxGradeGroup);

r($requirementOperateMenu1) && p('0:name,hint,disabled')   && e('change,只有激活状态的需求，才能进行变更,1');           //查看用户需求列表操作菜单第一个链接
r($requirementOperateMenu1) && p('1:name,data-toggle,url') && e('submitreview,modal,/requirement-submitReview-1.html'); //查看用户需求列表操作菜单第二个链接
r($requirementOperateMenu1) && p('3:name,data-toggle,url') && e('close,modal,/requirement-close-1-.html');              //查看用户需求列表操作菜单第四个链接
r($requirementOperateMenu1) && p('5:name,url')             && e('edit,/requirement-edit-1-default.html');               //查看用户需求列表操作菜单第六个链接

r($storyOperateMenu1)       && p('0:name,hint,url')         && e('change,变更,/story-change-2-.html');                  //查看软件需求详情操作菜单第一个链接
r($storyOperateMenu1)       && p('1:name,hint,disabled')    && e('review,该需求已是激活状态，无需评审,1');              //查看软件需求详情操作菜单第二个链接
r($storyOperateMenu1)       && p('3:name,data-toggle,url')  && e('close,modal,/story-close-2-.html');                   //查看软件需求详情操作菜单第四个链接
r($storyOperateMenu1)       && p('5:name,url')              && e('edit,/story-edit-2-default.html');                    //查看软件需求详情操作菜单第六个链接
r($storyOperateMenu1)       && p('6:name,url')              && e('testcase,/testcase-create-1-0-0--0-2.html');          //查看软件需求详情操作菜单第七个链接

r($storyOperateMenu2)       && p('8:name,className,url') && e('batchCreate,~~,~~');                                     //查看软件需求详情操作菜单第九个链接
