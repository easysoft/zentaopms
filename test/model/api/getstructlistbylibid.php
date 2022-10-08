#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/api.class.php';
su('admin');

/**

title=测试 apiModel->getStructListByLibID();
cid=1
pid=1

用libID获取刚插入的数据结构 >> 910

*/

global $tester;
$api = new apiTest();

$normalStruct = new stdclass();
$normalStruct->name      = 'struct';
$normalStruct->lib       = 910;
$normalStruct->type      = 'formData';
$normalStruct->attribute = '[{"field":"field1","paramsType":"string","required":true,"desc":"desc","structType":"formData","sub":1,"key":"l2u5qy3jc1se6ghigll","children":[]}]';
$normalStruct->desc      = '';
$normalStruct->addedBy   = $tester->app->user->account;
$normalStruct->addedDate = helper::now();

$struct = $api->createStructTest($normalStruct, false);

r($api->getStructListByLibIDTest($normalStruct->lib)) && p('0:lib') && e('910'); //用libID获取刚插入的数据结构