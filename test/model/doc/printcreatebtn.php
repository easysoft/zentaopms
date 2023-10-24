#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/doc.class.php';

$product = zdTable('product');
$product->id->range('1');
$product->name->range('产品1');
$product->gen(1);

$project = zdTable('project');
$project->id->range('1-2');
$project->name->range('项目1,执行1');
$project->type->range('project,sprint');
$project->acl->range('open');
$project->gen(2);

$doclib = zdTable('doclib');
$doclib->id->range('1-5');
$doclib->name->range('产品库1,产品接口库1,项目库1,执行库1,自定义库1');
$doclib->type->range('product,api,project,execution,custom');
$doclib->vision->range('rnd');
$doclib->product->range('1{2},0{3}');
$doclib->project->range('0{2},1{2},0');
$doclib->execution->range('0{3},2,0');
$doclib->gen(5);

$module = zdTable('module');
$module->id->range('1-10');
$module->name->range('第一章,第二章,第一章第一节,第一章第二节,第三章');
$module->parent->range('0{2},1{2},0{3},6{2},0{2}');
$module->root->range('1{5},2{5}');
$module->type->range('doc{5},api{5}');
$module->gen(10);

su('admin');

/**

title=测试 docModel->printCreateBtn();
cid=1
pid=1

测试产品库下的创建按钮 >> 创建文档
测试项目库下的创建按钮 >> 创建文档
测试执行库下的创建按钮 >> Word
测试自定义库下的创建按钮 >> PPT

*/

global $tester;
$tester->loadModel('doc');
$libList['product']   = $tester->doc->getLibById(1);
$libList['project']   = $tester->doc->getLibById(3);
$libList['execution'] = $tester->doc->getLibById(4);
$libList['custom']    = $tester->doc->getLibById(5);

r(strip_tags($tester->doc->printCreateBtn($libList['product'], 'product', 1, 1)))     && p() && e('创建文档'); // 测试产品库下的创建按钮
r(strip_tags($tester->doc->printCreateBtn($libList['project'], 'project', 1, 0)))     && p() && e('创建文档'); // 测试项目库下的创建按钮
r(strip_tags($tester->doc->printCreateBtn($libList['execution'], 'execution', 2, 0))) && p() && e('Word'); // 测试执行库下的创建按钮
r(strip_tags($tester->doc->printCreateBtn($libList['custom'], 'custom', 0, 0)))       && p() && e('PPT'); // 测试自定义库下的创建按钮
