#!/usr/bin/env php
<?php

/**

title=测试 indexZen::getViewMethodForAssetLib();
timeout=0
cid=16766

- 步骤1：doc对象，assetLibType为practice @practiceView
- 步骤2：doc对象，assetLibType为component @componentView
- 步骤3：不存在的doc对象 @0
- 步骤4：非doc对象，有assetViewMethod配置 @storyView
- 步骤5：无效objectType @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备（根据需要配置）
$docTable = zenData('doc');
$docTable->id->range('1-10');
$docTable->vision->range('rnd');
$docTable->project->range('0');
$docTable->product->range('0');
$docTable->execution->range('0');
$docTable->lib->range('1-5');
$docTable->title->range('测试文档1,测试文档2,测试文档3,测试文档4,测试文档5,测试文档6,测试文档7,测试文档8,测试文档9,测试文档10');
$docTable->type->range('text');
$docTable->status->range('normal');
$docTable->acl->range('open');
$docTable->assetLibType->range('practice,practice,practice,component,component,component,\'\',\'\',\'\',component');
$docTable->addedBy->range('admin');
$docTable->addedDate->range('`2023-01-01 10:00:00`');
$docTable->deleted->range('0');
$docTable->gen(10);

$storyTable = zenData('story');
$storyTable->id->range('1-10');
$storyTable->vision->range('rnd');
$storyTable->product->range('1-3');
$storyTable->title->range('测试需求1,测试需求2,测试需求3,测试需求4,测试需求5,测试需求6,测试需求7,测试需求8,测试需求9,测试需求10');
$storyTable->type->range('story');
$storyTable->status->range('active');
$storyTable->lib->range('1,1,1,2,2,2,0,0,0,0');
$storyTable->openedBy->range('admin');
$storyTable->openedDate->range('`2023-01-01 10:00:00`');
$storyTable->deleted->range('0');
$storyTable->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$indexTest = new indexZenTest();

global $config;

// 设置maxVersion配置
$config->maxVersion = '1.0.0';
if(!isset($config->action)) $config->action = new stdclass();
$config->action->assetViewMethod = array(
    'story' => 'storyView',
    'task' => 'taskView'
);

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($indexTest->getViewMethodForAssetLibTest(1, 'doc')) && p() && e('practiceView'); // 步骤1：doc对象，assetLibType为practice
r($indexTest->getViewMethodForAssetLibTest(4, 'doc')) && p() && e('componentView'); // 步骤2：doc对象，assetLibType为component  
r($indexTest->getViewMethodForAssetLibTest(99, 'doc')) && p() && e('0'); // 步骤3：不存在的doc对象
r($indexTest->getViewMethodForAssetLibTest(1, 'story')) && p() && e('storyView'); // 步骤4：非doc对象，有assetViewMethod配置
r($indexTest->getViewMethodForAssetLibTest(1, 'invalid')) && p() && e('0'); // 步骤5：无效objectType