#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

$product = zenData('product');
$product->id->range('1');
$product->name->range('产品1');
$product->gen(1);

$project = zenData('project');
$project->id->range('1-2');
$project->name->range('项目1,执行1');
$project->type->range('project,sprint');
$project->acl->range('open');
$project->gen(2);

$doclib = zenData('doclib');
$doclib->id->range('1-5');
$doclib->name->range('产品库1,产品接口库1,项目库1,执行库1,自定义库1');
$doclib->type->range('product,api,project,execution,custom');
$doclib->vision->range('rnd');
$doclib->product->range('1{2},0{3}');
$doclib->project->range('0{2},1,0{2}');
$doclib->execution->range('0{3},2,0');
$doclib->gen(5);

$module = zenData('module');
$module->id->range('1-10');
$module->name->range('第一章,第二章,第一章第一节,第一章第二节,第三章');
$module->parent->range('0{2},1{2},0{3},6{2},0{2}');
$module->root->range('1{5},2{5}');
$module->type->range('doc{5},api{5}');
$module->gen(10);

su('admin');

/**

title=测试 docModel->getModuleTree();
cid=16111

- 检查产品库高亮第0条的active属性 @1
- 检查产品库是否有同时高亮问题第1条的active属性 @0
- 检查产品库是否有同时高亮问题第2条的active属性 @0
- 检查产品库子模块树的数量 @2
- 检查接口库高亮第0条的active属性 @1
- 检查接口库是否有同时高亮问题第1条的active属性 @0
- 检查接口库是否有同时高亮问题第2条的active属性 @0
- 检查接口库子模块树的数量 @2

*/

global $tester;

$rootIDList   = array(1, 2);
$moduleIDList = array(0, 1, 6);
$typeList     = array('doc', 'api');

$docChildrenModule = $tester->loadModel('doc')->getModuleTree($rootIDList[0], $moduleIDList[1], $typeList[0]);
$apiChildrenModule = $tester->loadModel('doc')->getModuleTree($rootIDList[1], $moduleIDList[2], $typeList[1]);

r($docChildrenModule)                     && p('0:active') && e('1');      // 检查产品库高亮
r($docChildrenModule)                     && p('1:active') && e('0');      // 检查产品库是否有同时高亮问题
r($docChildrenModule)                     && p('2:active') && e('0');      // 检查产品库是否有同时高亮问题
r(count($docChildrenModule[0]->children)) && p()           && e('2');      // 检查产品库子模块树的数量
r($apiChildrenModule)                     && p('0:active') && e('1');      // 检查接口库高亮
r($apiChildrenModule)                     && p('1:active') && e('0');      // 检查接口库是否有同时高亮问题
r($apiChildrenModule)                     && p('2:active') && e('0');      // 检查接口库是否有同时高亮问题
r(count($apiChildrenModule[0]->children)) && p()           && e('2');      // 检查接口库子模块树的数量
