#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=变更研发需求测试
timeout=0
cid=80

- 缺少需求名称，变更失败
 -  测试结果 @变更需求表单页提示信息正确
 -  最终测试状态 @SUCCESS
- 使用默认选项变更研发需求 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/changestory.ui.class.php';

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
$story->product->range('1');
$story->module->range('0');
$story->plan->range('0');
$tester = new changeStoryTester();
$tester->login();

$storys = array();
$storys['null']    = '';
$storys['default'] = '变更后需求';

r($tester->changeStory($storys['null']))    && p('message,status')  && e('变更需求表单页面提示信息正确,SUCCESS'); // 缺少需求名称，变更失败
r($tester->changeStory($storys['default'])) && p('message,status') && e('变更需求成功,SUCCESS');                 // 使用默认选项变更需求,详情页信息对应

$tester->closeBrowser();
