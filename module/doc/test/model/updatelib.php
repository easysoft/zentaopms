#!/usr/bin/env php
<?php

/**

title=测试 docModel->updateLib();
cid=1

- 正常修改产品文档库
 - 第0条的field属性 @name
 - 第0条的old属性 @产品文档主库26
 - 第0条的new属性 @编辑文档库
- 正常修改项目文档库
 - 第0条的field属性 @name
 - 第0条的old属性 @项目文档主库18
 - 第0条的new属性 @编辑文档库
- 正常修改执行文档库
 - 第0条的field属性 @name
 - 第0条的old属性 @执行文档主库20
 - 第0条的new属性 @编辑文档库
- 正常修改自定义文档库
 - 第0条的field属性 @name
 - 第0条的old属性 @自定义文档库6
 - 第0条的new属性 @编辑文档库
- 正常修改无产品第product条的0属性 @『产品库』应当是数字。
- 正常修改无项目第project条的0属性 @『project』应当是数字。
- 正常修改无迭代第execution条的0属性 @『迭代库』应当是数字。
- 正常修改无文档库名第name条的0属性 @『库名称』不能为空。

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

zdTable('project')->config('execution')->gen(6);
zdTable('project')->config('project')->gen(2);
zdTable('doclib')->config('doclib')->gen(30);
zdTable('user')->gen(5);
su('admin');

$docLibIds = array(26, 18, 20, 6, 11);
$type      = array('product', 'project', 'execution', 'custom', 'mine');
$product   = array('', '2');
$project   = array('', '60');
$execution = array('', '102');
$name      = array('', '编辑文档库');
$acl       = array('', 'default', 'custom', 'private');
$groups    = '1,2,3';
$users     = 'admin,dev1,dev10';

$updateProduct   = array('type' => $type[0], 'product' => $product[1], 'name' => $name[1], 'acl' => $acl[1]);
$updateProject   = array('type' => $type[1], 'project' => $project[1], 'name' => $name[1], 'acl' => $acl[1]);
$updateExecution = array('type' => $type[2], 'execution' => $execution[1], 'name' => $name[1], 'acl' => $acl[1]);
$updateCustom    = array('type' => $type[3], 'name' => $name[1], 'acl' => $acl[1]);
$updateMine      = array('type' => $type[4], 'name' => $name[1], 'acl' => $acl[3]);
$customLib       = array('type' => $type[0], 'product' => $product[1], 'name' => $name[1], 'acl' => $acl[2], 'groups' => $groups,  'users' => $users);
$noProduct       = array('type' => $type[0], 'product' => $product[0], 'name' => $name[1], 'acl' => $acl[1]);
$noProject       = array('type' => $type[1], 'project' => $project[0], 'name' => $name[1], 'acl' => $acl[1]);
$noExecution     = array('type' => $type[2], 'execution' => $execution[0], 'name' => $name[1], 'acl' => $acl[1]);
$noName          = array('type' => $type[0], 'product' => $product[1], 'name' => $name[0], 'acl' => $acl[0]);

$docTester = new docTest();
r($docTester->updateLibTest($docLibIds[0], $updateProduct))   && p('0:field,old,new') && e('name,产品文档主库26,编辑文档库'); // 正常修改产品文档库
r($docTester->updateLibTest($docLibIds[1], $updateProject))   && p('0:field,old,new') && e('name,项目文档主库18,编辑文档库'); // 正常修改项目文档库
r($docTester->updateLibTest($docLibIds[2], $updateExecution)) && p('0:field,old,new') && e('name,执行文档主库20,编辑文档库'); // 正常修改执行文档库
r($docTester->updateLibTest($docLibIds[3], $updateCustom))    && p('0:field,old,new') && e('name,自定义文档库6,编辑文档库');  // 正常修改自定义文档库
r($docTester->updateLibTest($docLibIds[0], $noProduct))       && p('product:0')       && e('『产品库』应当是数字。');         // 正常修改无产品
r($docTester->updateLibTest($docLibIds[0], $noProject))       && p('project:0')       && e('『project』应当是数字。');        // 正常修改无项目
r($docTester->updateLibTest($docLibIds[0], $noExecution))     && p('execution:0')     && e('『迭代库』应当是数字。');         // 正常修改无迭代
r($docTester->updateLibTest($docLibIds[0], $noName))          && p('name:0')          && e('『库名称』不能为空。');           // 正常修改无文档库名
