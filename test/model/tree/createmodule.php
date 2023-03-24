#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tree.class.php';
$module = zdTable('module');
$module->id->range(1);
$module->name->range('父模块1');
$module->grade->range('1');
$module->root->range('1');
$module->type->range('doc');
$module->order->range('10');
$module->gen(1);

su('admin');

/**

title=测试 treeModel->createModule();
cid=1
pid=1

测试空数据创建模块 >> 『目录名称』不能为空。
测试模块名称为空 >> 『目录名称』不能为空。
测试创建文档模块 >> 模块1,1
测试创建名称重复的模块 >> 模块名“模块1”已经存在！
测试创建文档子模块 >> 模块1的子模块1,2
测试创建API模块 >> api的模块1,1

*/

$nameList       = array('', '模块1', '模块1的子模块1', 'api的模块1');
$createTypeList = array('','same', 'child');
$moduleTypeList = array('','doc', 'api');

$emptyData       = array('name' => $nameList[0], 'createType' => $createTypeList[0], 'moduleType' => $moduleTypeList[0], 'parentID' => 0);
$nameEmptyData   = array('name' => $nameList[0], 'createType' => $createTypeList[0], 'moduleType' => $moduleTypeList[0], 'parentID' => 0);
$docModuleData   = array('name' => $nameList[1], 'createType' => $createTypeList[2], 'moduleType' => $moduleTypeList[1], 'parentID' => 0);
$repeatNameData  = array('name' => $nameList[1], 'createType' => $createTypeList[1], 'moduleType' => $moduleTypeList[1], 'parentID' => 0);
$childModuleData = array('name' => $nameList[2], 'createType' => $createTypeList[2], 'moduleType' => $moduleTypeList[1], 'parentID' => 1);
$apiModuleData   = array('name' => $nameList[3], 'createType' => $createTypeList[1], 'moduleType' => $moduleTypeList[2], 'parentID' => 0);

$treeTester = new treeTest();

r($treeTester->createModuleTest($emptyData))       && p()             && e('『目录名称』不能为空。');    // 测试空数据创建模块
r($treeTester->createModuleTest($nameEmptyData))   && p()             && e('『目录名称』不能为空。');    // 测试模块名称为空
r($treeTester->createModuleTest($docModuleData))   && p('name,grade') && e('模块1,1');                   // 测试创建文档模块
r($treeTester->createModuleTest($repeatNameData))  && p()             && e('模块名“模块1”已经存在！'); // 测试创建名称重复的模块
r($treeTester->createModuleTest($childModuleData)) && p('name,grade') && e('模块1的子模块1,2')         ; // 测试创建文档子模块
r($treeTester->createModuleTest($apiModuleData))   && p('name,grade') && e('api的模块1,1');              // 测试创建API模块
