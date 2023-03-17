#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/design.class.php';
su('admin');

/**

title=测试 designModel->update();
cid=1
pid=1

修改设计类型 >> type,HLDS,DDS
修改设计需求 >> story,0,17
修改设计产品 >> product,31,21
修改设计名称 >> name,这是一个设计1,修改设计
修改设计详情 >> desc,这是设计描述1,详情修改
设计名称不能为空 >> 『设计名称』不能为空。
设计类型不能为空 >> 『设计类型』不能为空。

*/
$designIDList = array('1', '2', '3');

$updateType    = array('type' => 'DDS');
$updateStory   = array('story' => '17');
$updateProduct = array('product' => '21');
$updateName    = array('name' => '修改设计');
$updateDesc    = array('desc' => '详情修改');
$noName        = array('name' => '');
$noType        = array('type' => '');

$design = new designTest();

r($design->updateTest($designIDList[0],$updateType))    && p('0:field,old,new') && e('type,HLDS,DDS');              //修改设计类型
r($design->updateTest($designIDList[1],$updateStory))   && p('0:field,old,new') && e('story,0,17');                 //修改设计需求
r($design->updateTest($designIDList[2],$updateProduct)) && p('0:field,old,new') && e('product,31,21');              //修改设计产品
r($design->updateTest($designIDList[0],$updateName))    && p('0:field,old,new') && e('name,这是一个设计1,修改设计');//修改设计名称
r($design->updateTest($designIDList[0],$updateDesc))    && p('0:field,old,new') && e('desc,这是设计描述1,详情修改');//修改设计详情
r($design->updateTest($designIDList[0],$noName))        && p('name:0')          && e('『设计名称』不能为空。');     //设计名称不能为空
r($design->updateTest($designIDList[0],$noType))        && p('type:0')          && e('『设计类型』不能为空。');     //设计类型不能为空

