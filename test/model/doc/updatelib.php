#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/doc.class.php';
su('admin');

/**

title=测试 docModel->updateLib();
cid=1
pid=1

正常修改产品文档库 >> type,product,project
正常修改项目文档库 >> project,27,11
正常修改执行文档库 >> product,0,1
正常修改自定义文档库 >> product,17,1
正常修改无type >> type,custom,
正常修改无产品 >> 『产品库』应当是数字。
正常修改无项目 >> 『project』应当是数字。』
正常修改无迭代 >> 『迭代库』应当是数字。
正常修改无文档库名 >> 『文档库名称』不能为空。

*/
$docLibIds = array('17', '117', '217');
$type      = array('product', 'project', 'execution', 'custom', '');
$product   = array('', '1');
$project   = array('', '11');
$execution = array('', '101');
$name      = array('', '新建文档库');
$acl       = array('', 'default', 'custom');
$groups    = array('1', '2', '3');
$users     = array('admin', 'dev1', 'dev10');

$updateProduct   = array('type' => $type[0], 'product' => $product[1], 'name' => $name[1], 'acl' => $acl[1]);
$updateProject   = array('type' => $type[1], 'project' => $project[1], 'name' => $name[1], 'acl' => $acl[1]);
$updateExecution = array('type' => $type[2], 'execution' => $execution[1], 'name' => $name[1], 'acl' => $acl[1]);
$updateCustom    = array('type' => $type[3], 'name' => $name[1], 'acl' => $acl[1]);
$customLib       = array('type' => $type[0], 'product' => $product[1], 'name' => $name[1], 'acl' => $acl[2], 'groups' => $groups,  'users' => $users);
$noType          = array('type' => $type[4], 'product' => $product[1], 'name' => $name[1], 'acl' => $acl[1]);
$noProduct       = array('type' => $type[0], 'product' => $product[0], 'name' => $name[1], 'acl' => $acl[1]);
$noProject       = array('type' => $type[1], 'project' => $project[0], 'name' => $name[1], 'acl' => $acl[1]);
$noExecution     = array('type' => $type[2], 'execution' => $execution[0], 'name' => $name[1], 'acl' => $acl[1]);
$noName          = array('type' => $type[0], 'product' => $product[1], 'name' => $name[0], 'acl' => $acl[0]);

$doc = new docTest();

r($doc->updateLibTest($docLibIds[0],$updateProject))   && p('0:field,old,new') && e('type,product,project');     //正常修改产品文档库
r($doc->updateLibTest($docLibIds[1],$updateProduct))   && p('1:field,old,new') && e('project,27,11');            //正常修改项目文档库
r($doc->updateLibTest($docLibIds[2],$updateExecution)) && p('2:field,old,new') && e('product,0,1');              //正常修改执行文档库
r($doc->updateLibTest($docLibIds[0],$updateCustom))    && p('1:field,old,new') && e('product,17,1');             //正常修改自定义文档库
r($doc->updateLibTest($docLibIds[0],$noType))          && p('0:field,old,new') && e('type,custom,');             //正常修改无type
r($doc->updateLibTest($docLibIds[0],$noProduct))       && p('product:0')       && e('『产品库』应当是数字。');   //正常修改无产品
r($doc->updateLibTest($docLibIds[0],$noProject))       && p('project:0')       && e('『project』应当是数字。』');//正常修改无项目
r($doc->updateLibTest($docLibIds[0],$noExecution))     && p('execution:0')     && e('『迭代库』应当是数字。');   //正常修改无迭代
r($doc->updateLibTest($docLibIds[0],$noName))          && p('name:0')          && e('『文档库名称』不能为空。'); //正常修改无文档库名

