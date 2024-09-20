#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=编辑研发需求测试
timeout=0
cid=80

- 编辑需求的来源后的链接检查
 - 属性module @story
 - 属性method @view
- 编辑研发需求
- 成功 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/editstory.ui.class.php';

$product = zenData('product');
$product->id->range('1');
$product->program->range('0');
$product->name->range('产品1');
$product->shadow->range('0');
$product->bind->range('0');
$product->acl->range('open');
$product->createdBy->range('admin');
$product->vision->range('rnd');
$product->gen(1);

$story = zenData('story');
$story->id->range('1-3');
$story->root->range('1-3');
$story->path->range('`,1,`, `,2,`, `,3,`');
$story->grade->range('1');
$tester = new editStoryTester();
$tester->login();

$storyFrom = '客户';

r($tester->editStory($storyFrom)) && p('module,method')  && e('story,view'); // 编辑需求后跳转页面检查
r($tester->editStory($storyFrom)) && p('message,status') && e('编辑需求成功,SUCCESS'); // 编辑需求成功

$tester->closeBrowser();
