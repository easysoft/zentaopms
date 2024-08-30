#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=变更研发需求测试
timeout=0
cid=80

- 缺少需求名称，变更失败
 -  测试结果 @创建产品表单页提示信息正确
 -  最终测试状态 @SUCCESS
- 使用默认选项变更研发需求 最终测试状态 @SUCCESS
- 创建正常需求后检查创建需求信息是否正确
 - 属性module @story
 - 属性method @view

*/
chdir(__DIR__);
include '../lib/changestory.ui.class.php';

$tester = new changeStoryTester();
$tester->login();

$storys = array();
$storys['null']    = '';
$storys['default'] = '变更后需求';

r($tester->changeStory($storys['null']))    && p('message,status')  && e('创建需求页面名称为空提示正确,SUCCESS'); // 缺少需求名称，变更失败
r($tester->changeStory($storys['default'])) && p('message, status') && e('变更需求成功,SUCCESS');                 // 使用默认选项变更需求,详情页信息对应

$tester->closeBrowser();
