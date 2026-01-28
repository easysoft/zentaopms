#!/usr/bin/env php
<?php
/**

title=productpanModel->getByIDList();
timeout=0
cid=17630

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('productplan')->loadYaml('productplan')->gen(10);
zenData('user')->gen(5);
su('admin');

$planIdList = array(array(), array(1), array(100));

global $tester,$app;
$app->moduleName = 'productplan';
$app->rawModule = 'productplan';
$tester->loadModel('productplan');

r($tester->productplan->getByIDList($planIdList[0])) && p()                     && e('0');         // 测试传入空的ID列表，获取计划列表信息
r($tester->productplan->getByIDList($planIdList[1])) && p('1:id,product,title') && e('1,1,计划1'); // 测试传入一个ID列表，获取计划列表信息
r($tester->productplan->getByIDList($planIdList[2])) && p()                     && e('0');         // 测试传入一个不存在的ID列表，获取计划列表信息
