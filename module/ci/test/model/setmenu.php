#!/usr/bin/env php
<?php

/**

title=测试 ciModel::setMenu();
timeout=0
cid=15592

- 执行ci模块的setMenuTest方法 第code条的link属性 @代码|repo|browse|repoID=1
- 执行ci模块的setMenuTest方法，参数是2 第code条的link属性 @代码|repo|browse|repoID=2
- 执行ci模块的setMenuTest方法，参数是5 属性mr @~~
- 执行ci模块的setMenuTest方法，参数是2, 'gitlab' 第code条的link属性 @代码|repo|browse|repoID=%s
- 执行ci模块的setMenuTest方法，参数是999 第code条的link属性 @代码|repo|browse|repoID=%s
- 执行ci模块的setMenuTest方法，参数是0, 'ci' 第code条的link属性 @代码|repo|browse|repoID=1
- 执行ci模块的setMenuTest方法，参数是1 第code条的link属性 @代码|repo|browse|repoID=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ci.unittest.class.php';

$repo = zenData('repo');
$repo->id->range('1-10');
$repo->product->range('1{5},2{5}');
$repo->name->range('Git仓库{3},SVN仓库{2},Gitlab仓库{3},Github仓库{2}');
$repo->SCM->range('Git{3},SVN{2},Gitlab{3},Github{2}');
$repo->serviceHost->range('1-5');
$repo->deleted->range('0{8},1{2}');
$repo->gen(10);

zenData('pipeline')->gen(5);
su('admin');

$ci = new ciTest();

r($ci->setMenuTest(0)) && p('code:link') && e('代码|repo|browse|repoID=1');
r($ci->setMenuTest(2)) && p('code:link') && e('代码|repo|browse|repoID=2');
r($ci->setMenuTest(5)) && p('mr') && e('~~');
r($ci->setMenuTest(2, 'gitlab')) && p('code:link') && e('代码|repo|browse|repoID=%s');
r($ci->setMenuTest(999)) && p('code:link') && e('代码|repo|browse|repoID=%s');
r($ci->setMenuTest(0, 'ci')) && p('code:link') && e('代码|repo|browse|repoID=1');
r($ci->setMenuTest(1)) && p('code:link') && e('代码|repo|browse|repoID=1');