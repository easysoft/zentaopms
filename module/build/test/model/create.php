#!/usr/bin/env php
<?php
/**

title=测试 buildModel->create();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/build.class.php';

zdTable('build')->gen(0);
zdTable('branch')->gen(10);
zdTable('product')->gen(10);
zdTable('project')->gen(10);
su('admin');

$executionIDList = array('101', '131', '161');
$normalExecution = array('execution' => $executionIDList[0], 'product' => 1, 'name' => '新增版本一', 'builder' => 'admin');
$waterallBuild   = array('execution' => $executionIDList[1], 'product' => 2, 'name' => '新增瀑布版本一', 'builder' => 'user10');
$kanbanBuild     = array('execution' => $executionIDList[2], 'product' => 3, 'name' => '新增看板版本一', 'builder' => 'dev10');
$noName          = array('execution' => $executionIDList[0], 'product' => 1, 'name' => '', 'builder' => 'admin');
$noProduct       = array('execution' => $executionIDList[0], 'product' => 0, 'name' => '新增无产品版本一', 'builder' => 'admin');
$noBuilder       = array('execution' => $executionIDList[0], 'product' => 1, 'name' => '新增无创建者版本一', 'builder' => '');

$build = new buildTest();

r($build->createTest($normalExecution)) && p('name')      && e('新增版本一');            //敏捷执行新增版本
r($build->createTest($waterallBuild))   && p('product')   && e('2');                     //瀑布执行新增版本
r($build->createTest($kanbanBuild))     && p('builder')   && e('dev10');                 //看板执行新增版本
r($build->createTest($normalExecution)) && p('name:0')    && e('『名称编号』已经有『新增版本一』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。');//重复名称测试
r($build->createTest($noName))          && p('name:0')    && e('『名称编号』不能为空。');//名称为空测试
r($build->createTest($noProduct))       && p('product:0') && e('『所属产品』不能为空。');    //产品为空测试
r($build->createTest($noBuilder))       && p('builder:0') && e('『构建者』不能为空。');  //构建者为空测试
