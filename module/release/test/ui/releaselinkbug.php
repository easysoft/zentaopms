#!/usr/bin/env php
<?php

/**

title=项目发布关联和移除Bug
timeout=0
cid=73

- 项目发布关联bug
 - 测试结果 @发布关联bug成功
 - 最终测试状态 @SUCCESS
- 单个移除bug
 - 测试结果 @单个移除bug成功
 - 最终测试状态 @SUCCESS
- 移除全部bug
 - 测试结果 @移除全部bug成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/ui/releaselinkbug.ui.class.php';

$product = zenData('product');
$product->id->range('1');
$product->name->range('产品1');
$product->type->range('normal');
$product->gen(1);

$system = zenData('system');
$system->id->range('1');
$system->product->range('1');
$system->name->range('应用AAA');
$system->status->range('active');
$system->integrated->range('0');
$system->createdBy->range('admin');
$system->gen(1);

$release = zenData('release');
$release->id->range('1');
$release->product->range('1');
$release->branch->range('0');
$release->name->range('发布1');
$release->system->range('1');
$release->status->range('wait');
$release->stories->range('[]');
$release->bugs->range('[]');
$release->desc->range('描述111');
$release->deleted->range('0');
$release->gen(1);

$bug = zenData('bug');
$bug->id->range('1-5');
$bug->project->range('1');
$bug->product->range('1');
$bug->execution->range('2');
$bug->title->range('Bug1, Bug2, Bug3, Bug4, Bug5');
$bug->status->range('active{2}, resolved{2}, closed{1}');
$bug->assignedTo->range('[]');
$bug->gen(5);

$tester = new releaseLinkBugTester();
$tester->login();

r($tester->linkBug())        && p('message,status') && e('发布关联bug成功,SUCCESS'); // 项目发布关联bug
r($tester->unlinkBug())      && p('message,status') && e('单个移除bug成功,SUCCESS'); // 单个移除bug
r($tester->batchUnlinkBug()) && p('message,status') && e('移除全部bug成功,SUCCESS'); // 移除全部bug

$tester->closeBrowser();
