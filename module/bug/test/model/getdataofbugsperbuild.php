#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('bug')->loadYaml('build')->gen(10);
zenData('build')->gen(10);

/**

title=bugModel->getDataOfBugsPerBuild();
timeout=0
cid=15366

- 获取版本为空 bug 数
 - 属性name @未设定
 - 属性value @9
- 获取主干 bug 数
 - 属性name @主干
 - 属性value @6
- 获取没关联影响版本的 bug 数
 - 属性name @未设定
 - 属性value @1
- 获取项目版本版本1 bug 数
 - 属性name @项目11版本1
 - 属性value @2
- 获取项目版本版本2 bug 数
 - 属性name @项目12版本2
 - 属性value @2

*/

$bug = new bugModelTest();
$result = $bug->getDataOfBugsPerBuildTest();
r($result[''])      && p('name,value') && e('未设定,9');      // 获取版本为空 bug 数
r($result['trunk']) && p('name,value') && e('主干,6');        // 获取主干 bug 数
r($result[0])       && p('name,value') && e('未设定,1');      // 获取没关联影响版本的 bug 数
r($result[1])       && p('name,value') && e('项目11版本1,2'); // 获取项目版本版本1 bug 数
r($result[2])       && p('name,value') && e('项目12版本2,2'); // 获取项目版本版本2 bug 数
