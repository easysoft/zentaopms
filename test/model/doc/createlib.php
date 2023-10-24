#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/doc.class.php';
su('admin');

/**

title=测试 docModel->createLib();
cid=1
pid=1

新建公有产品文档库 >> product;新建文档库
新建公有项目文档库 >> project;default
新建公有执行文档库 >> execution;rnd
新建公有自定义文档库 >> custom;0
新建私有自定义文档库 >> product;1,2,3;admin,dev1,dev10
新建无产品产品文档库 >> 『产品库』应当是数字。
新建无项目项目文档库 >> 『project』应当是数字。
新建无执行执行文档库 >> 『迭代库』应当是数字。

*/
$type      = array('product', 'project', 'execution', 'custom', '');
$product   = array('', '1');
$project   = array('', '11');
$execution = array('', '101');
$name      = array('', '新建文档库');
$acl       = array('', 'default', 'custom');
$groups    = array('1', '2', '3');
$users     = array('admin', 'dev1', 'dev10');

$createProduct   = array('type' => $type[0], 'product' => $product[1], 'name' => $name[1], 'acl' => $acl[1]);
$createProject   = array('type' => $type[1], 'project' => $project[1], 'name' => $name[1], 'acl' => $acl[1]);
$createExecution = array('type' => $type[2], 'execution' => $execution[1], 'name' => $name[1], 'acl' => $acl[1]);
$createCustom    = array('type' => $type[3], 'name' => $name[1], 'acl' => $acl[1]);
$customLib       = array('type' => $type[0], 'product' => $product[1], 'name' => $name[1], 'acl' => $acl[2], 'groups' => $groups,  'users' => $users);
$noProduct       = array('type' => $type[0], 'product' => $product[0], 'name' => $name[1], 'acl' => $acl[1]);
$noProject       = array('type' => $type[1], 'project' => $project[0], 'name' => $name[1], 'acl' => $acl[1]);
$noExecution     = array('type' => $type[2], 'execution' => $execution[0], 'name' => $name[1], 'acl' => $acl[1]);

$doc = new docTest();

r($doc->createLibTest($createProduct))   && p('type;name')         && e('product;新建文档库');            //新建公有产品文档库
r($doc->createLibTest($createProject))   && p('type;acl')          && e('project;default');               //新建公有项目文档库
r($doc->createLibTest($createExecution)) && p('type;vision')       && e('execution;rnd');                 //新建公有执行文档库
r($doc->createLibTest($createCustom))    && p('type;project')      && e('custom;0');                      //新建公有自定义文档库
r($doc->createLibTest($customLib))       && p('type;groups;users') && e('product;1,2,3;admin,dev1,dev10');//新建私有自定义文档库
r($doc->createLibTest($noProduct))       && p('product:0')         && e('『产品库』应当是数字。');        //新建无产品产品文档库
r($doc->createLibTest($noProject))       && p('project:0')         && e('『project』应当是数字。');       //新建无项目项目文档库
r($doc->createLibTest($noExecution))     && p('execution:0')       && e('『迭代库』应当是数字。');        //新建无执行执行文档库
