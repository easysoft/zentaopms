#!/usr/bin/env php
<?php

/**

title=测试 docModel->getTemplateScopes();
timeout=0
cid=16133

- 获取产品范围
 - 第1条的id属性 @1
 - 第1条的name属性 @产品
- 获取项目范围
 - 第2条的id属性 @2
 - 第2条的name属性 @项目
- 获取执行范围
 - 第3条的id属性 @3
 - 第3条的name属性 @执行
- 获取个人范围
 - 第4条的id属性 @4
 - 第4条的name属性 @个人
- 获取自定义范围
 - 第10条的id属性 @10
 - 第10条的name属性 @自定义范围

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);
su('admin');

global $tester;
$template = $tester->loadModel('doc');

$scope = zenData('doclib');
$scope->id->range('1-10');
$scope->type->range('template');
$scope->vision->range('rnd{4},or{3},lite{2},rnd');
$scope->name->range('产品,项目,执行,个人,市场,项目,个人,产品,个人,自定义范围');
$scope->main->range('1{9},0');
$scope->gen(10);

r($template->getTemplateScopes()) && p('1:id,name')  && e('1,产品');        // 获取产品范围
r($template->getTemplateScopes()) && p('2:id,name')  && e('2,项目');        // 获取项目范围
r($template->getTemplateScopes()) && p('3:id,name')  && e('3,执行');        // 获取执行范围
r($template->getTemplateScopes()) && p('4:id,name')  && e('4,个人');        // 获取个人范围
r($template->getTemplateScopes()) && p('10:id,name') && e('10,自定义范围'); // 获取自定义范围
