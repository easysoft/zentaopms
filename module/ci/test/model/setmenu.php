#!/usr/bin/env php
<?php

/**

title=测试 ciModel->setMenu();
timeout=0
cid=1

- 没有传递代码库ID，使用SESSION中的repoID第code条的link属性 @代码|repo|browse|repoID=1
- 传递代码库ID，使用传递的repoID第code条的link属性 @代码|repo|browse|repoID=2
- SVN代码库，不显示MR属性mr @~~
- DevOps模块主菜单不需要替换代码库ID第code条的link属性 @代码|repo|browse|repoID=%s

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/ci.class.php';

zdTable('repo')->config('repo')->gen(5);
su('admin');

$ci = new ciTest();

r($ci->setMenuTest(0)) && p('code:link') && e('代码|repo|browse|repoID=1'); // 没有传递代码库ID，使用SESSION中的repoID
r($ci->setMenuTest(2)) && p('code:link') && e('代码|repo|browse|repoID=2'); // 传递代码库ID，使用传递的repoID

r($ci->setMenuTest(5)) && p('mr') && e('~~'); // SVN代码库，不显示MR

r($ci->setMenuTest(2, 'gitlab')) && p('code:link') && e('代码|repo|browse|repoID=%s'); // DevOps模块主菜单不需要替换代码库ID