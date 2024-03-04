#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->getRepoPairs();
timeout=0
cid=1

- 获取type为repo的结果集
 - 属性1 @[gitlab] testHtml
 - 属性4 @[svn] testSvn
- 获取type为repo的结果数量 @4
- 获取指定projectID的结果集属性1 @[gitlab] testHtml
- 获取指定projectID的结果数量 @1
- 获取type为repo的结果集，showScm参数为false属性2 @project1

*/

zdTable('repo')->config('repo')->gen(4);
zdTable('product')->gen(10);
zdTable('project')->gen(20);
zdTable('projectproduct')->gen(10);

$repo = $tester->loadModel('repo');

$typeList = array('project','repo');
$projectID = 11;

$result = $repo->getRepoPairs($typeList[1]);
r($result)        && p('1,4') && e('[gitlab] testHtml,[svn] testSvn'); // 获取type为repo的结果集
r(count($result)) && p()      && e('4'); //获取type为repo的结果数量

$result = $repo->getRepoPairs($typeList[0], $projectID);
r($result)        && p('1') && e('[gitlab] testHtml'); // 获取指定projectID的结果集
r(count($result)) && p()    && e('1'); //获取指定projectID的结果数量

r($repo->getRepoPairs($typeList[1], 0, false)) && p('2') && e('project1'); //获取type为repo的结果集，showScm参数为false