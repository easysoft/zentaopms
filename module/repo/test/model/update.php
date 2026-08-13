#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 repoModel->update();
timeout=0
cid=18109

- 执行repo模块的updateTest方法，参数是1, $data1, true
 - 第0条的field属性 @name
 - 第0条的old属性 @testHtml
 - 第0条的new属性 @repo1
- 执行repo模块的updateTest方法，参数是1, $data2, true
 - 第0条的field属性 @product
 - 第0条的old属性 @1
 - 第0条的new属性 @2
- 执行repo模块的updateTest方法，参数是1, $data3, true
 - 第0条的field属性 @defaultBranch
 - 第0条的old属性 @main
 - 第0条的new属性 @develop
- 执行repo模块的updateTest方法，参数是1, $data4, true
 - 第0条的field属性 @desc
 - 第0条的old属性 @原始描述
 - 第0条的new属性 @更新描述
- 执行repo模块的updateTest方法，参数是1, $data5, true
 - 第0条的field属性 @spaceID
 - 第0条的old属性 @1
 - 第0条的new属性 @2
- 执行repo模块的updateTest方法，参数是1, $data6, true
 - 第0条的field属性 @acl
 - 第0条的old属性 @private
 - 第0条的new属性 @open

*/

zenData('ops_repo')->gen(0);
zenData('ops_repouser')->gen(0);

$repo = zenData('ops_repo');
$repo->id->range('1');
$repo->spaceID->range('1');
$repo->product->range('1');
$repo->name->range('testHtml');
$repo->desc->range('原始描述');
$repo->scmType->range('git');
$repo->gitUID->range('update-repo-gituid-1');
$repo->defaultBranch->range('main');
$repo->acl->range('private');
$repo->status->range('active');
$repo->deleted->range('0');
$repo->gen(1);

$repoUser = zenData('ops_repouser');
$repoUser->repo->range('1');
$repoUser->account->range('admin');
$repoUser->gen(1);

su('admin');

$_SERVER['REQUEST_URI'] = 'http://unittest.com';

$data1 = (object)array('space' => 1, 'product' => '1', 'name' => 'repo1', 'desc' => '原始描述', 'defaultBranch' => 'main', 'acl' => 'private', 'members' => 'admin');
$data2 = (object)array('space' => 1, 'product' => '2', 'name' => 'repo1', 'desc' => '原始描述', 'defaultBranch' => 'main', 'acl' => 'private', 'members' => 'admin');
$data3 = (object)array('space' => 1, 'product' => '2', 'name' => 'repo1', 'desc' => '原始描述', 'defaultBranch' => 'develop', 'acl' => 'private', 'members' => 'admin');
$data4 = (object)array('space' => 1, 'product' => '2', 'name' => 'repo1', 'desc' => '更新描述', 'defaultBranch' => 'develop', 'acl' => 'private', 'members' => 'admin');
$data5 = (object)array('space' => 2, 'product' => '2', 'name' => 'repo1', 'desc' => '更新描述', 'defaultBranch' => 'develop', 'acl' => 'private', 'members' => 'admin');
$data6 = (object)array('space' => 2, 'product' => '2', 'name' => 'repo1', 'desc' => '更新描述', 'defaultBranch' => 'develop', 'acl' => 'open', 'members' => '');

$repo = new repoModelTest();

r($repo->updateTest(1, $data1, true)) && p('0:field,old,new') && e('name,testHtml,repo1');
r($repo->updateTest(1, $data2, true)) && p('0:field,old,new') && e('product,1,2');
r($repo->updateTest(1, $data3, true)) && p('0:field,old,new') && e('defaultBranch,main,develop');
r($repo->updateTest(1, $data4, true)) && p('0:field,old,new') && e('desc,原始描述,更新描述');
r($repo->updateTest(1, $data5, true)) && p('0:field,old,new') && e('spaceID,1,2');
r($repo->updateTest(1, $data6, true)) && p('0:field,old,new') && e('acl,private,open');
