#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->parseBugComment();
timeout=0
cid=1

- 解析修复bug属性3 @3
- 解析修复多个bug
 - 属性3 @3
 - 属性5 @5
 - 属性12 @12

*/

$repo = new repoTest();

$fixBugComment      = 'Fix bug#3';
$fixBugComment2     = 'Fix bug#3,5,12';

r($repo->parseBugCommentTest($fixBugComment))      && p('3')        && e('3'); //解析修复bug
r($repo->parseBugCommentTest($fixBugComment2))     && p('3,5,12')   && e('3,5,12'); //解析修复多个bug