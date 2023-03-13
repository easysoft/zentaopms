#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/build.class.php';
su('admin');

/**

title=测试 buildModel->update();
cid=1
pid=1

修改项目版本 >> name,项目版本版本1,修改版本一
修改执行版本 >> name,执行版本版本11,修改执行版本一
修改版本路径 >> scmPath,,http://www.baidu.com
名称为空测试 >> 『名称编号』不能为空。
构建者为空测试 >> 『构建者』不能为空。

*/

$buildIDList = array('1', '11');

$normalExecution = array('name' => '修改版本一', 'builder' => 'admin');
$executionBuild  = array('name' => '修改执行版本一', 'builder' => 'admin');
$pathBuild       = array('name' => '修改路径版本一', 'scmPath' => 'http://www.baidu.com', 'filePath' => 'http://www.lujing.com');
$noName          = array('name' => '', 'builder' => 'admin');
$noBuilder       = array('name' => '修改无创建者版本一', 'builder' => '');

$build = new buildTest();

r($build->updateTest($buildIDList[0], $normalExecution)) && p('0:field,old,new') && e('name,项目版本版本1,修改版本一');      //修改项目版本
r($build->updateTest($buildIDList[1], $executionBuild))  && p('0:field,old,new') && e('name,执行版本版本11,修改执行版本一'); //修改执行版本
r($build->updateTest($buildIDList[0], $pathBuild))       && p('1:field,old,new') && e('scmPath,,http://www.baidu.com');      //修改版本路径
r($build->updateTest($buildIDList[0], $noName))          && p('name:0')          && e('『名称编号』不能为空。');             //名称为空测试
r($build->updateTest($buildIDList[0], $noBuilder))       && p('builder:0')       && e('『构建者』不能为空。');               //构建者为空测试

