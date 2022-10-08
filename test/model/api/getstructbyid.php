#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/api.class.php';
su('admin');

/**

title=测试 apiModel->getStructByID();
cid=1
pid=1

获取刚创建的数据结构 >> struct

*/

global $tester;
$api = new apiTest();

$normalStruct = new stdclass();
$normalStruct->name      = 'struct';
$normalStruct->type      = 'formData';
$normalStruct->attribute = '[{"field":"field1","paramsType":"string","required":true,"desc":"desc","structType":"formData","sub":1,"key":"l2u5qy3jc1se6ghigll","children":[]}]';
$normalStruct->desc      = '';
$normalStruct->addedBy   = $tester->app->user->account;
$normalStruct->addedDate = helper::now();

$struct = $api->createStructTest($normalStruct, false);
r($api->getStructByIDTest($struct->id)) && p('name') && e('struct'); //获取刚创建的数据结构