#!/usr/bin/env php
<?php

/**

title=测试 docZen::assignVarsForUploadDocs();
timeout=0
cid=16181

- 测试产品空间下上传文档
 - 属性objectType @product
 - 属性libID @1
 - 属性linkType @product
 - 属性hasOptionMenu @1
- 测试项目空间下上传文档
 - 属性objectType @project
 - 属性libID @2
 - 属性linkType @project
- 测试自定义空间下上传文档
 - 属性objectType @custom
 - 属性libID @7
 - 属性linkType @custom
 - 属性hasSpaces @1
- 测试我的空间下上传文档
 - 属性objectType @mine
 - 属性libID @9
 - 属性linkType @mine
 - 属性hasSpaces @1
- 测试执行空间下上传文档
 - 属性objectType @execution
 - 属性objectID @3
- 测试不传入任何参数使用默认值
 - 属性objectType @product
 - 属性linkType @product

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$doclib = zenData('doclib');
$doclib->id->range('1-10');
$doclib->name->range('产品文档库,项目文档库,执行文档库,自定义库1,项目文档库2,自定义库2,自定义库3,产品文档库2,我的文档库,我的文档库2');
$doclib->type->range('product,project,execution,custom,project,custom,custom,product,mine,mine');
$doclib->product->range('1,0,0,0,0,0,0,2,0,0');
$doclib->project->range('0,2,0,0,0,0,0,0,0,0');
$doclib->execution->range('0,0,3,0,0,0,0,0,0,0');
$doclib->parent->range('0');
$doclib->acl->range('open');
$doclib->main->range('1');
$doclib->addedBy->range('admin');
$doclib->vision->range('rnd');
$doclib->order->range('1-10');
$doclib->deleted->range('0');
$doclib->gen(10);
zenData('product')->loadYaml('assignvarsforuploaddocs/product', false, 2)->gen(5);
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
zenData('module')->loadYaml('assignvarsforuploaddocs/module', false, 2)->gen(5);
zenData('doc')->loadYaml('assignvarsforuploaddocs/doc', false, 2)->gen(10);
zenData('group')->gen(3);
zenData('user')->gen(5);

su('admin');

$docTest = new docZenTest();

r($docTest->assignVarsForUploadDocsTest('product', 1, 1, 0, '')) && p('objectType,libID,linkType,hasOptionMenu') && e('product,1,product,1'); // 测试产品空间下上传文档
r($docTest->assignVarsForUploadDocsTest('project', 2, 2, 0, '')) && p('objectType,libID,linkType') && e('project,2,project'); // 测试项目空间下上传文档
r($docTest->assignVarsForUploadDocsTest('custom', 0, 7, 0, '')) && p('objectType,libID,linkType,hasSpaces') && e('custom,7,custom,1'); // 测试自定义空间下上传文档
r($docTest->assignVarsForUploadDocsTest('mine', 0, 9, 0, '')) && p('objectType,libID,linkType,hasSpaces') && e('mine,9,mine,1'); // 测试我的空间下上传文档
r($docTest->assignVarsForUploadDocsTest('execution', 3, 3, 0, '')) && p('objectType,objectID') && e('execution,3'); // 测试执行空间下上传文档
r($docTest->assignVarsForUploadDocsTest('product', 0, 0, 0, '')) && p('objectType,linkType') && e('product,product'); // 测试不传入任何参数使用默认值