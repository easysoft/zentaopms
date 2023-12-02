#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/bug.class.php';
su('admin');

zdTable('bug')->config('build')->gen(10);
zdTable('build')->gen(10);

/**

title=bugModel->getDataOfBugsPerBuild();
timeout=0
cid=1

- 获取主干 bug 数
 - 第trunk条的name属性 @主干
 - 第trunk条的value属性 @6

- 获取没关联影响版本的 bug 数
 - 第0条的name属性 @未设定
 - 第0条的value属性 @1

- 获取项目版本版本1 bug 数
 - 第1条的name属性 @项目11版本1
 - 第1条的value属性 @2

- 获取项目版本版本2 bug 数
 - 第2条的name属性 @项目12版本2
 - 第2条的value属性 @2

*/

$bug = new bugTest();
r($bug->getDataOfBugsPerBuildTest()) && p('trunk:name,value') && e('主干,6');          // 获取主干 bug 数
r($bug->getDataOfBugsPerBuildTest()) && p('0:name,value')     && e('未设定,1');        // 获取没关联影响版本的 bug 数
r($bug->getDataOfBugsPerBuildTest()) && p('1:name,value')     && e('项目11版本1,2'); // 获取项目版本版本1 bug 数
r($bug->getDataOfBugsPerBuildTest()) && p('2:name,value')     && e('项目12版本2,2'); // 获取项目版本版本2 bug 数
