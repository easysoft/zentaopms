#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->update();
timeout=0
cid=1

- 更新版本库1名字
 - 第0条的field属性 @name
 - 第0条的old属性 @testHtml
 - 第0条的new属性 @repo1
- 更新版本库1所属产品
 - 第0条的field属性 @product
 - 第0条的old属性 @1
 - 第0条的new属性 @2
- 更新版本库1相关项目
 - 第0条的field属性 @projects
 - 第0条的old属性 @~~
 - 第0条的new属性 @3
- 更新版本库1仓库第webhook条的0属性 @changeServerProject

*/

zdTable('repo')->config('repo')->gen(1);

$_SERVER['REQUEST_URI'] = 'http://unittest.com';

$data1 = (object)array('product' => '1', 'SCM' => 'Gitlab', 'name' => 'repo1', 'serviceHost' => 1, 'serviceProject' => 2, 'encrypt' => 'plain', 'path' => '42');
$data2 = (object)array('product' => '2', 'SCM' => 'Gitlab', 'name' => 'repo1', 'serviceHost' => 1, 'serviceProject' => 2, 'encrypt' => 'plain', 'path' => '42');
$data3 = (object)array('product' => '2', 'projects' => '3','SCM' => 'Gitlab', 'name' => 'repo1', 'serviceHost' => 1, 'serviceProject' => 2, 'encrypt' => 'plain', 'path' => '42');
$data4 = (object)array('product' => '2', 'projects' => '3','SCM' => 'Gitlab', 'name' => 'repo1', 'serviceHost' => 1, 'serviceProject' => 1, 'encrypt' => 'plain', 'path' => '42');

$repo = new repoTest();
r($repo->updateTest(1, $data1, true)) && p('0:field,old,new') && e('name,testHtml,repo1'); //更新版本库1名字
r($repo->updateTest(1, $data2, true)) && p('0:field,old,new') && e('product,1,2');        //更新版本库1所属产品
r($repo->updateTest(1, $data3, true)) && p('0:field,old,new') && e('projects,~~,3');      //更新版本库1相关项目
r($repo->updateTest(1, $data4, true)) && p('webhook:0') && e('changeServerProject');      //更新版本库1仓库