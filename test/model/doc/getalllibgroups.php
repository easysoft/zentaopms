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
$doclib->type->range('product{2},project,execution{2}');
$doclib->vision->range('rnd');
$doclib->name->range('产品库1,产品库2,项目库1,执行库1,执行库2');
$doclib->product->range('1{2},0{3}');
$doclib->project->range('0{2},1,0{2}');
$doclib->execution->range('0{3},2{2}');
$doclib->gen(5);

zdTable('user')->gen(5);
su('admin');

/**

title=测试 docModel->getAllLibGroups();
cid=1
pid=1

查询产品库 >> 产品1
查询项目库 >> 项目库1
查询执行库 >> 执行1
查询产品库统计 >> 1
查询项目库统计 >> 1
查询执行库统计 >> 1

*/
global $tester;
$doc = $tester->loadModel('doc');

$appendLibs = array('1');

r($doc->getAllLibGroups($appendLibs)['product'])          && p('1:name')   && e('产品1');   //查询产品库
r($doc->getAllLibGroups($appendLibs)['project'])          && p('3')        && e('项目库1'); //查询项目库
r($doc->getAllLibGroups($appendLibs)['execution'])        && p('2:name')   && e('执行1');   //查询执行库
r(count($doc->getAllLibGroups($appendLibs)['product']))   && p()           && e('1');       //查询产品库统计
r(count($doc->getAllLibGroups($appendLibs)['project']))   && p()           && e('1');       //查询项目库统计
r(count($doc->getAllLibGroups($appendLibs)['execution'])) && p()           && e('1');       //查询执行库统计
