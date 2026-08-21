#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 repoZen->linkobject();
timeout=0
cid=0

- 执行test模块的linkObjectTest方法，参数是1, 'HEAD', 'story', $post  @1
- 执行test模块的linkObjectTest方法，参数是1, 'HEAD', 'story', $post  @1
- 执行test模块的linkObjectTest方法，参数是1, 'HEAD', 'story', $post  @1
- 执行test模块的linkObjectTest方法，参数是1, 'HEAD', 'story', $post  @1
- 执行test模块的linkObjectTest方法，参数是1, 'HEAD', 'story', $post  @1

*/

zenData('ops_repo')->gen(0);
zenData('ops_repohistory')->gen(0);

$repo = zenData('ops_repo');
$repo->id->range('1');
$repo->spaceID->range('1');
$repo->product->range('1');
$repo->name->range('repo-zen-link');
$repo->scmType->range('git');
$repo->gitUID->range('repo-zen-link-gituid');
$repo->acl->range('private');
$repo->status->range('active');
$repo->deleted->range('0');
$repo->gen(1);

$history = zenData('ops_repohistory');
$history->id->range('1');
$history->repo->range('1');
$history->revision->range('HEAD');
$history->commit->range('1');
$history->comment->range('repo zen link commit');
$history->committer->range('admin');
$history->time->range('20260806 000000:0')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$history->gen(1);

su('admin');
$test = new repoZenTest();
$post = array('stories' => array(), 'bugs' => array(), 'tasks' => array());

r($test->linkObjectTest(1, 'HEAD', 'story', $post)) && p() && e('1');
r($test->linkObjectTest(1, 'HEAD', 'story', $post)) && p() && e('1');
r($test->linkObjectTest(1, 'HEAD', 'story', $post)) && p() && e('1');
r($test->linkObjectTest(1, 'HEAD', 'story', $post)) && p() && e('1');
r($test->linkObjectTest(1, 'HEAD', 'story', $post)) && p() && e('1');
