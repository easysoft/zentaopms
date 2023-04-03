#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/design.class.php';
su('admin');

/**

title=测试 designModel->create();
cid=1
pid=1

不输入名称创建设计 >> 『设计名称』不能为空。
创建hlds设计 >> hlds设计
创建dds设计 >> 2
创建dbds设计 >> 41
创建ads设计 >> ADS
不输入类型创建设计 >> 『设计类型』不能为空。

*/
$projectIDList = array('11', '31', '61');
$products      = array('1', '21', '41');
$type          = array('HLDS', 'DDS', 'DBDS', 'ADS');

$hldsDesign       = array('product' => $products[0], 'story' => '1', 'type' => $type[0], 'name' => 'hlds设计');
$ddsDesign        = array('product' => $products[1], 'story' => '2', 'type' => $type[1], 'name' => 'dds设计');
$dbdsDesign       = array('product' => $products[2], 'story' => '3', 'type' => $type[2], 'name' => 'dbds设计');
$adsDesign        = array('product' => $products[0], 'story' => '1', 'type' => $type[3], 'name' => 'ads设计');
$noProductDesign  = array('story' => '1', 'type' => $type[3], 'name' => 'ads设计');
$noStoryDesign    = array('product' => $products[0], 'type' => $type[3], 'name' => 'ads设计');
$noTypeDesign     = array('product' => $products[0], 'story' => '1', 'name' => 'ads设计');
$noNameDesign     = array('product' => $products[0], 'story' => '1', 'type' => $type[3]);

$design = new designTest();
r($design->createTest($projectIDList[0], $noNameDesign))    && p('name:0')  && e('『设计名称』不能为空。'); //不输入名称创建设计
r($design->createTest($projectIDList[0], $hldsDesign))      && p('name')    && e('hlds设计');               //创建hlds设计
r($design->createTest($projectIDList[1], $ddsDesign))       && p('story')   && e('2');                      //创建dds设计
r($design->createTest($projectIDList[2], $dbdsDesign))      && p('product') && e('41');                     //创建dbds设计
r($design->createTest($projectIDList[0], $adsDesign))       && p('type')    && e('ADS');                    //创建ads设计
r($design->createTest($projectIDList[0], $noProductDesign)) && p('product') && e('');                       //不输入产品创建设计
r($design->createTest($projectIDList[0], $noStoryDesign))   && p('story')   && e('');                       //不输入需求创建设计
r($design->createTest($projectIDList[0], $noTypeDesign))    && p('type:0')  && e('『设计类型』不能为空。'); //不输入类型创建设计

