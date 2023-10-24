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

title=测试 docModel->getLibTree();
cid=1
pid=1

获取产品树并检查高亮 >> 产品库1,1
获取项目树 >> 项目库1
获取执行树 >> 执行库1
获取自定义树 >> 自定义库1
检查产品库个数 >> 3
检查项目库个数 >> 3
检查执行库个数 >> 2
检查自定义库个数 >> 1

*/

global $tester;
$tester->loadModel('doc');

$libIDList    = array(1, 3, 4, 5);
$moduleIDList = array(1, 6);
$objectIDList = array(0, 1, 2);
$typeList     = array('product', 'project', 'execution', 'custom');

$libs['product']   = $tester->doc->getLibsByObject('product', 1);
$libs['project']   = $tester->doc->getLibsByObject('project', 1);
$libs['execution'] = $tester->doc->getLibsByObject('execution', 2);
$libs['custom']    = $tester->doc->getLibsByObject('custom', 0);

$productLibTree   = $tester->doc->getLibTree($libIDList[0], $libs['product'], $typeList[0], $moduleIDList[0], $objectIDList[1]);
$projectLibTree   = $tester->doc->getLibTree($libIDList[1], $libs['project'], $typeList[1], $moduleIDList[1], $objectIDList[1]);
$executionLibTree = $tester->doc->getLibTree($libIDList[2], $libs['execution'], $typeList[2], $moduleIDList[1], $objectIDList[2]);
$customLibTree    = $tester->doc->getLibTree($libIDList[3], $libs['custom'], $typeList[3], $moduleIDList[1], $objectIDList[0]);

r($productLibTree)          && p('0:name,active') && e('产品库1,1'); // 获取产品树并检查高亮
r($projectLibTree)          && p('0:name')        && e('项目库1');   // 获取项目树
r($executionLibTree)        && p('0:name')        && e('执行库1');   // 获取执行树
r($customLibTree)           && p('0:name')        && e('自定义库1'); // 获取自定义树
r(count($productLibTree))   && p()                && e('3');         // 检查产品库个数
r(count($projectLibTree))   && p()                && e('3');         // 检查项目库个数
r(count($executionLibTree)) && p()                && e('2');         // 检查执行库个数
r(count($customLibTree))    && p()                && e('1');         // 检查自定义库个数
