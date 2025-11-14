#!/usr/bin/env php
<?php

/**

title=测试 releaseZen::assignVarsForView();
timeout=0
cid=18019

- 步骤~~：正常情况测试需求类型
 - 属性type @story
 - 属性hasStories @~~
 - 属性hasUsers @~~
- 步骤2：正常情况测试Bug类型
 - 属性type @bug
 - 属性hasBugs @~~
 - 属性hasUsers @~~
- 步骤3：正常情况测试遗留Bug类型
 - 属性type @leftBug
 - 属性hasLeftBugs @~~
 - 属性hasUsers @~~
- 步骤4：测试参数传递
 - 属性type @story
 - 属性link @true
 - 属性param @test
 - 属性orderBy @title_asc
- 步骤5：测试包含关联发布的情况
 - 属性type @story
 - 属性hasStories @~~
 - 属性hasUsers @~~
 - 属性hasActions @~~

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/releasezen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zenData('product')->gen(5);
zenData('story')->gen(10);
zenData('bug')->gen(10);
zenData('user')->gen(5);

// 使用zenData生成release数据，不指定系统字段
$release = zenData('release');
$release->id->range('1-5');
$release->product->range('1-5');
$release->name->range('发布1,发布2,发布3,发布4,发布5');
$release->stories->range('1,2,3{2},,1,2{2}');
$release->bugs->range('1,2{2},,1{2}');
$release->leftBugs->range('1{2},,2{2}');
$release->status->range('normal{5}');
$release->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$releaseTest = new releaseZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$release1 = $releaseTest->objectModel->getByID(1);
$release2 = $releaseTest->objectModel->getByID(2);
$release3 = $releaseTest->objectModel->getByID(3);
$release4 = $releaseTest->objectModel->getByID(4);
$release5 = $releaseTest->objectModel->getByID(5);

r($release1 ? $releaseTest->assignVarsForViewTest($release1, 'story', '', '', 'id_desc') : array('error' => 'no_release'))           && p('type,hasStories,hasUsers')            && e('story,~~,~~');               // 步骤~~：正常情况测试需求类型
r($release2 ? $releaseTest->assignVarsForViewTest($release2, 'bug', '', '', 'id_desc') : array('error' => 'no_release'))             && p('type,hasBugs,hasUsers')               && e('bug,~~,~~');                 // 步骤2：正常情况测试Bug类型
r($release3 ? $releaseTest->assignVarsForViewTest($release3, 'leftBug', '', '', 'severity_desc') : array('error' => 'no_release'))   && p('type,hasLeftBugs,hasUsers')           && e('leftBug,~~,~~');             // 步骤3：正常情况测试遗留Bug类型
r($release4 ? $releaseTest->assignVarsForViewTest($release4, 'story', 'true', 'test', 'title_asc') : array('error' => 'no_release')) && p('type,link,param,orderBy')             && e('story,true,test,title_asc'); // 步骤4：测试参数传递
r($release5 ? $releaseTest->assignVarsForViewTest($release5, 'story', '', '', 'id_desc') : array('error' => 'no_release'))           && p('type,hasStories,hasUsers,hasActions') && e('story,~~,~~,~~');            // 步骤5：测试包含关联发布的情况
