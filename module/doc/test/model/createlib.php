#!/usr/bin/env php
<?php

/**

title=测试 docModel->createLib();
timeout=0
cid=1

- 新建公有产品文档库
 - 属性type @product
 - 属性name @新建文档库
- 新建公有项目文档库
 - 属性type @project
 - 属性acl @default
- 新建公有产品api库
 - 属性type @product
 - 属性name @新建文档库
- 新建公有项目api库
 - 属性type @project
 - 属性acl @default
- 新建公有执行文档库
 - 属性type @execution
 - 属性vision @rnd
- 新建我的私有文档库
 - 属性type @mine
 - 属性project @0
- 新建公有api文档库
 - 属性type @api
 - 属性project @0
- 新建公有自定义文档库
 - 属性type @custom
 - 属性project @0
- 新建私有自定义文档库
 - 属性type @custom
 - 属性groups @1,2,3
 - 属性users @admin,dev1,dev10
- 新建无产品产品文档库第product条的0属性 @『产品库』应当是数字。
- 新建无项目项目文档库第project条的0属性 @『project』应当是数字。
- 新建无执行执行文档库第execution条的0属性 @『迭代库』应当是数字。

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

zdTable('user')->gen(5);
su('admin');

$type      = array('product', 'project', 'execution', 'custom', 'mine', 'api', '');
$libType   = array('wiki', 'api');
$product   = array('', 1);
$project   = array('', 11);
$execution = array('', 101);
$name      = array('', '新建文档库');
$acl       = array('', 'default', 'custom', 'private');
$groups    = '1,2,3';
$users     = 'admin,dev1,dev10';

$createProduct   = array('type' => $type[0], 'product' => $product[1], 'name' => $name[1], 'acl' => $acl[1]);
$createProject   = array('type' => $type[1], 'project' => $project[1], 'name' => $name[1], 'acl' => $acl[1]);
$createExecution = array('type' => $type[2], 'execution' => $execution[1], 'name' => $name[1], 'acl' => $acl[1]);
$createMine      = array('type' => $type[4], 'name' => $name[1], 'acl' => $acl[3]);
$createApi       = array('type' => $type[5], 'name' => $name[1], 'acl' => $acl[1]);
$createCustom    = array('type' => $type[3], 'name' => $name[1], 'acl' => $acl[1]);
$customLib       = array('type' => $type[3], 'name' => $name[1], 'acl' => $acl[2], 'groups' => $groups,  'users' => $users);
$noProduct       = array('type' => $type[0], 'product' => $product[0], 'name' => $name[1], 'acl' => $acl[1]);
$noProject       = array('type' => $type[1], 'project' => $project[0], 'name' => $name[1], 'acl' => $acl[1]);
$noExecution     = array('type' => $type[2], 'execution' => $execution[0], 'name' => $name[1], 'acl' => $acl[1]);

$docTester = new docTest();
r($docTester->createLibTest($createProduct, $type[0], $libType[0])) && p('type;name')              && e('product;新建文档库');            // 新建公有产品文档库
r($docTester->createLibTest($createProject, $type[1], $libType[0])) && p('type;acl')               && e('project;default');               // 新建公有项目文档库
r($docTester->createLibTest($createProduct, $type[0], $libType[1])) && p('type;name')              && e('product;新建文档库');            // 新建公有产品api库
r($docTester->createLibTest($createProject, $type[1], $libType[1])) && p('type;acl')               && e('project;default');               // 新建公有项目api库
r($docTester->createLibTest($createExecution, $type[2]))            && p('type;vision')            && e('execution;rnd');                 // 新建公有执行文档库
r($docTester->createLibTest($createMine))                           && p('type;project')           && e('mine;0');                        // 新建我的私有文档库
r($docTester->createLibTest($createApi))                            && p('type;project')           && e('api;0');                         // 新建公有api文档库
r($docTester->createLibTest($createCustom))                         && p('type;project')           && e('custom;0');                      // 新建公有自定义文档库
r($docTester->createLibTest($customLib))                            && p('type;groups;users', ';') && e('custom;1,2,3;admin,dev1,dev10'); // 新建私有自定义文档库
r($docTester->createLibTest($noProduct))                            && p('product:0')              && e('『产品库』应当是数字。');        // 新建无产品产品文档库
r($docTester->createLibTest($noProject))                            && p('project:0')              && e('『project』应当是数字。');       // 新建无项目项目文档库
r($docTester->createLibTest($noExecution))                          && p('execution:0')            && e('『迭代库』应当是数字。');        // 新建无执行执行文档库
