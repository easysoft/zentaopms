#!/usr/bin/env php
<?php
/**

title=测试 designModel->create();
cid=1

- 创建概要设计
 - 属性name @概要设计
 - 属性project @11
 - 属性product @1
 - 属性type @HLDS
- 创建详细设计
 - 属性name @详细设计
 - 属性project @11
 - 属性product @1
 - 属性type @DDS
- 创建数据库设计
 - 属性name @数据库设计
 - 属性project @11
 - 属性product @1
 - 属性type @DBDS
- 创建接口设计
 - 属性name @接口设计
 - 属性project @11
 - 属性product @1
 - 属性type @ADS
- 创建关联需求设计
 - 属性name @关联需求设计
 - 属性project @11
 - 属性product @1
 - 属性type @DDS
- 创建未关联产品设计第product条的0属性 @『所属产品』不能为空。
- 创建没有类型设计第type条的0属性 @『设计类型』不能为空。
- 创建没有名称设计第name条的0属性 @『设计名称』不能为空。

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/design.class.php';

zdTable('designspec')->gen(0);
zdTable('design')->gen(0);
zdTable('user')->gen(5);

$products  = array(0, 1);
$stories   = array(0, 1);
$types     = array('HLDS', 'DDS', 'DBDS', 'ADS', '');

$hldsDesign      = array('product' => $products[1], 'story' => $stories[0], 'type' => $types[0], 'name' => '概要设计');
$ddsDesign       = array('product' => $products[1], 'story' => $stories[0], 'type' => $types[1], 'name' => '详细设计');
$dbdsDesign      = array('product' => $products[1], 'story' => $stories[0], 'type' => $types[2], 'name' => '数据库设计');
$adsDesign       = array('product' => $products[1], 'story' => $stories[0], 'type' => $types[3], 'name' => '接口设计');
$storyDesign     = array('product' => $products[1], 'story' => $stories[1], 'type' => $types[1], 'name' => '关联需求设计');
$noProductDesign = array('product' => $products[0], 'story' => $stories[0], 'type' => $types[3], 'name' => '未关联产品设计');
$noTypeDesign    = array('product' => $products[0], 'story' => $stories[0], 'type' => $types[4], 'name' => '没有类型设计');
$noNameDesign    = array('product' => $products[1], 'story' => $stories[0], 'type' => $types[1], 'name' => '');

$designTester = new designTest();
r($designTester->createTest($hldsDesign))      && p('name,project,product,type') && e('概要设计,11,1,HLDS');     // 创建概要设计
r($designTester->createTest($ddsDesign))       && p('name,project,product,type') && e('详细设计,11,1,DDS');      // 创建详细设计
r($designTester->createTest($dbdsDesign))      && p('name,project,product,type') && e('数据库设计,11,1,DBDS');   // 创建数据库设计
r($designTester->createTest($adsDesign))       && p('name,project,product,type') && e('接口设计,11,1,ADS');      // 创建接口设计
r($designTester->createTest($storyDesign))     && p('name,project,product,type') && e('关联需求设计,11,1,DDS');  // 创建关联需求设计
r($designTester->createTest($noProductDesign)) && p('product:0')                 && e('『所属产品』不能为空。'); // 创建未关联产品设计
r($designTester->createTest($noTypeDesign))    && p('type:0')                    && e('『设计类型』不能为空。'); // 创建没有类型设计
r($designTester->createTest($noNameDesign))    && p('name:0')                    && e('『设计名称』不能为空。'); // 创建没有名称设计
