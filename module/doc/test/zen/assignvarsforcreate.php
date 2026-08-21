#!/usr/bin/env php
<?php

/**

title=测试 docZen::assignVarsForCreate();
timeout=0
cid=16179

- 测试产品空间下创建文档
 - 属性objectType @product
 - 属性libID @1
 - 属性objectID @1
 - 属性hasLib @1
 - 属性hasLibs @1
 - 属性hasGroups @1
 - 属性hasUsers @1
- 测试项目空间下创建文档
 - 属性objectType @project
 - 属性libID @2
 - 属性objectID @2
 - 属性hasLib @1
 - 属性hasLibs @1
 - 属性hasGroups @1
 - 属性hasUsers @1
- 测试执行空间下创建文档
 - 属性objectType @execution
 - 属性objectID @3
 - 属性hasGroups @1
 - 属性hasUsers @1
- 测试自定义空间下创建文档
 - 属性objectType @custom
 - 属性libID @4
 - 属性hasLib @1
 - 属性hasLibs @1
 - 属性hasGroups @1
 - 属性hasUsers @1
- 测试我的空间下创建文档
 - 属性objectType @mine
 - 属性libID @5
 - 属性moduleID @1
 - 属性docType @html
 - 属性hasLib @1
 - 属性hasLibs @1
 - 属性hasGroups @1
 - 属性hasUsers @1
- 测试传入不存在的libID参数
 - 属性objectType @product
 - 属性libID @999
 - 属性objectID @1
 - 属性hasLib @1
 - 属性hasLibs @1
- 测试不传入任何参数使用默认值
 - 属性objectType @product
 - 属性hasGroups @1
 - 属性hasUsers @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doczen.unittest.class.php';

$doclib = zenData('doclib');
$doclib->id->range('1-10');
$doclib->name->range('产品文档库,项目文档库,执行文档库,自定义文档库,我的文档库,产品接口库,项目接口库,测试文档库,开发文档库,需求文档库');
$doclib->type->range('product,project,execution,custom,mine,product,project,product,project,custom');
$doclib->product->range('1,0,0,0,0,2,0,1,0,0');
$doclib->project->range('0,2,2,0,0,0,3,0,2,0');
$doclib->execution->range('0,0,3,0,0,0,0,0,0,0');
$doclib->parent->range('0');
$doclib->acl->range('default');
$doclib->main->range('1,1,1,0,1,0,0,0,0,0');
$doclib->addedBy->range('admin');
$doclib->vision->range('rnd');
$doclib->order->range('1-10');
$doclib->deleted->range('0');
$doclib->gen(10);
zenData('product')->loadYaml('assignvarsforcreate/product', false, 2)->gen(5);
$project = zenData('project');
$project->id->range('1-5');
$project->name->range('项目1,项目2,项目3,项目4,项目5');
$project->type->range('project,project,sprint,project,project');
$project->project->range('0,0,1,0,0');
$project->model->range('scrum,waterfall,scrum,kanban,waterfall');
$project->status->range('doing');
$project->acl->range('open');
$project->openedBy->range('admin');
$project->begin->range('`2024-01-01`');
$project->end->range('`2024-12-31`');
$project->deleted->range('0');
$project->gen(5);
zenData('module')->loadYaml('assignvarsforcreate/module', false, 2)->gen(5);
zenData('group')->gen(3);
zenData('user')->gen(5);

su('admin');

$docTest = new docZenTest();

r($docTest->assignVarsForCreateTest('product', 1, 1, 0, '')) && p('objectType,libID,objectID,hasLib,hasLibs,hasGroups,hasUsers') && e('product,1,1,1,1,1,1'); // 测试产品空间下创建文档
r($docTest->assignVarsForCreateTest('project', 2, 2, 0, '')) && p('objectType,libID,objectID,hasLib,hasLibs,hasGroups,hasUsers') && e('project,2,2,1,1,1,1'); // 测试项目空间下创建文档
r($docTest->assignVarsForCreateTest('execution', 3, 3, 0, '')) && p('objectType,objectID,hasGroups,hasUsers') && e('execution,3,1,1'); // 测试执行空间下创建文档
r($docTest->assignVarsForCreateTest('custom', 0, 4, 0, '')) && p('objectType,libID,hasLib,hasLibs,hasGroups,hasUsers') && e('custom,4,1,1,1,1'); // 测试自定义空间下创建文档
r($docTest->assignVarsForCreateTest('mine', 0, 5, 1, 'html')) && p('objectType,libID,moduleID,docType,hasLib,hasLibs,hasGroups,hasUsers') && e('mine,5,1,html,1,1,1,1'); // 测试我的空间下创建文档
r($docTest->assignVarsForCreateTest('product', 1, 999, 0, '')) && p('objectType,libID,objectID,hasLib,hasLibs') && e('product,999,1,1,1'); // 测试传入不存在的libID参数
r($docTest->assignVarsForCreateTest('product', 0, 0, 0, '')) && p('objectType,hasGroups,hasUsers') && e('product,1,1'); // 测试不传入任何参数使用默认值