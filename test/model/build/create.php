#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/build.class.php';
su('admin');

/**

title=测试 buildModel->create();
cid=1
pid=1

敏捷执行新增版本 >> 新增版本一
瀑布执行新增版本 >> 2
看板执行新增版本 >> dev10
执行新增路径版本 >> http://www.baidu.com
重复名称测试 >> 『名称编号』已经有『新增版本一』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。
名称为空测试 >> 『名称编号』不能为空。
产品为空测试 >> 『产品』不能为空。
构建者为空测试 >> 『构建者』不能为空。

*/

$executionIDList = array('101', '131', '161');

$normalExecution = array('execution' => $executionIDList[0], 'product' => '1', 'name' => '新增版本一', 'builder' => 'admin');
$waterallBuild   = array('execution' => $executionIDList[1], 'product' => '2', 'name' => '新增瀑布版本一', 'builder' => 'user10');
$kanbanBuild     = array('execution' => $executionIDList[2], 'product' => '3', 'name' => '新增看板版本一', 'builder' => 'dev10');
$pathBuild       = array('execution' => $executionIDList[0], 'product' => '1', 'name' => '新增路径版本一', 'scmPath' => 'http://www.baidu.com', 'filePath' => 'http://www.lujing.com');
$noName          = array('execution' => $executionIDList[0], 'product' => '1', 'name' => '', 'builder' => 'admin');
$noProduct       = array('execution' => $executionIDList[0], 'product' => '', 'name' => '新增无产品版本一', 'builder' => 'admin');
$noBuilder       = array('execution' => $executionIDList[0], 'product' => '1', 'name' => '新增无创建者版本一', 'builder' => '');

$build = new buildTest();

r($build->createTest($executionIDList[0], $normalExecution)) && p('name')      && e('新增版本一');            //敏捷执行新增版本
r($build->createTest($executionIDList[1], $waterallBuild))   && p('product')   && e('2');                     //瀑布执行新增版本
r($build->createTest($executionIDList[2], $kanbanBuild))     && p('builder')   && e('dev10');                 //看板执行新增版本
r($build->createTest($executionIDList[0], $pathBuild))       && p('scmPath')   && e('http://www.baidu.com');  //执行新增路径版本
r($build->createTest($executionIDList[0], $normalExecution)) && p('name:0')    && e('『名称编号』已经有『新增版本一』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。');//重复名称测试
r($build->createTest($executionIDList[0], $noName))          && p('name:0')    && e('『名称编号』不能为空。');//名称为空测试
r($build->createTest($executionIDList[0], $noProduct))       && p('product:0') && e('『产品』不能为空。');    //产品为空测试
r($build->createTest($executionIDList[0], $noBuilder))       && p('builder:0') && e('『构建者』不能为空。');  //构建者为空测试

