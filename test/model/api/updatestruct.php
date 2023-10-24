#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/api.class.php';
su('admin');

/**

title=测试 apiModel->updateStruct();
cid=1
pid=1

正常的修改 >> editStruct
没有名称的修改 >> 『结构名』不能为空。

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

$data = array(
    'name'      => 'editStruct',
    'type'      => 'formData',
    'attribute' => '[{"field":"field1","paramsType":"string","required":true,"desc":"desc","structType":"formData","sub":1,"key":"l2u5qy3jc1se6ghigll","children":[]}]',
    'desc'      => '',
);

$normalEditStruct = $data;

$emptyNameEditStruct = $data;
$emptyNameEditStruct['name'] = '';

$struct = $api->createStructTest($normalStruct, false);
r($api->updateStructTest($struct->id, $normalEditStruct, false)) && p('0:new') && e('editStruct');                //正常的修改
r($api->updateStructTest($struct->id, $emptyNameEditStruct)) && p('name:0') && e('『结构名』不能为空。'); //没有名称的修改
