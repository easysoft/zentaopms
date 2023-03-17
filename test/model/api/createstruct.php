#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/api.class.php';
su('admin');

/**

title=测试 apiModel->createStruct();
cid=1
pid=1

创建数据结构 >> struct
创建没有结构名的数据结构 >> 『结构名』不能为空。

*/

global $tester;
$api = new apiTest();

$emptyNameStruct = new stdclass();
$emptyNameStruct->name      = '';
$emptyNameStruct->type      = 'formData';
$emptyNameStruct->attribute = '[{"field":"field1","paramsType":"string","required":true,"desc":"desc","structType":"formData","sub":1,"key":"l2u5qy3jc1se6ghigll","children":[]}]';
$emptyNameStruct->desc      = '';
$emptyNameStruct->addedBy   = $tester->app->user->account;
$emptyNameStruct->addedDate = helper::now();

$normalStruct = new stdclass();
$normalStruct->name      = 'struct';
$normalStruct->type      = 'formData';
$normalStruct->attribute = '[{"field":"field1","paramsType":"string","required":true,"desc":"desc","structType":"formData","sub":1,"key":"l2u5qy3jc1se6ghigll","children":[]}]';
$normalStruct->desc      = '';
$normalStruct->addedBy   = $tester->app->user->account;
$normalStruct->addedDate = helper::now();

r($api->createStructTest($normalStruct)) && p('name') && e('struct');                            //创建数据结构
r($api->createStructTest($emptyNameStruct)) && p('name:0') && e('『结构名』不能为空。'); //创建没有结构名的数据结构
