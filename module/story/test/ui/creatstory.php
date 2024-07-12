#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=创建研发需求测试
timeout=0
cid=80

- 缺少需求名称，创建失败
 -  测试结果 @创建产品表单页提示信息正确
 -  最终测试状态 @SUCCESS
- 使用默认选项创建研发需求 最终测试状态 @SUCCESS
- 创建正常需求后检查创建需求信息是否正确
 - 属性module @story
 - 属性method @view
- 创建正常产品成功 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/createstory.ui.class.php';

//zendata('story')->loadYaml('story', false, 2)->gen(10);
$tester = new createStoryTester();
$tester->login();

$storys = array();
$storys['null']       = '';
$storys['default']    = '默认需求';
$storys['childStory'] = '子需求';

r($tester->createDefault($storys['null']))       && p('message,status') && e('创建需求页面名称为空提示正确,SUCCESS'); // 缺少需求名称，创建失败
r($tester->createDefault($storys['default']))    && p('status')         && e('SUCCESS');                              // 使用默认选项创建需求,搜索后详情页信息对应

$tester->closeBrowser();
