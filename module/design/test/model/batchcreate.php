#!/usr/bin/env php
<?php
/**

title=测试 designModel->batchCreate();
cid=1

- 批量创建概要设计
 - 第0条的name属性 @概要设计1
 - 第0条的type属性 @HLDS
- 批量创建详细设计
 - 第0条的name属性 @详细设计1
 - 第0条的type属性 @DDS
- 批量创建数据库设计
 - 第0条的name属性 @数据库设计1
 - 第0条的type属性 @DBDS
- 批量创建接口设计
 - 第0条的name属性 @接口设计1
 - 第0条的type属性 @ADS
- 批量创建无类型设计 @『设计类型』不能为空。
- 批量创建所有类型设计
 - 第0条的name属性 @概要设计3
 - 第0条的type属性 @HLDS
- 批量创建需求关联设计
 - 第0条的name属性 @概要设计4
 - 第0条的type属性 @HLDS
 - 第0条的story属性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/design.class.php';

zdTable('design')->gen(0);
zdTable('user')->gen(5);

$hldsDesgins   = array(array('type' => 'HLDS', 'name' => '概要设计1'),   array('type' => 'HLDS', 'name' => '概要设计2'));
$ddsDesgins    = array(array('type' => 'DDS',  'name' => '详细设计1'),   array('type' => 'DDS',  'name' => '详细设计2'));
$dbdsDesgins   = array(array('type' => 'DBDS', 'name' => '数据库设计1'), array('type' => 'DBDS', 'name' => '数据库设计2'));
$adsDesgins    = array(array('type' => 'ADS',  'name' => '接口设计1'),   array('type' => 'ADS',  'name' => '接口设计2'));
$noTypeDesgins = array(array('type' => '',     'name' => '无类型设计1'), array('type' => '',     'name' => '无类型设计2'));
$allDesgins    = array(array('type' => 'HLDS', 'name' => '概要设计3'),   array('type' => 'DDS',  'name' => '详细设计3'), array('type' => 'DBDS', 'name' => '数据库设计3'), array('type' => 'ADS',  'name' => '接口设计3'));
$storyDesgins  = array(array('type' => 'HLDS', 'name' => '概要设计4', 'story' => 1), array('type' => 'DDS',  'name' => '详细设计4', 'story' => 1), array('type' => 'DBDS', 'name' => '数据库设计4', 'story' => 1), array('type' => 'ADS',  'name' => '接口设计4', 'story' => 1));

$designTester = new designTest();
r($designTester->batchCreateTest($hldsDesgins))   && p('0:name,type')       && e('概要设计1,HLDS');         // 批量创建概要设计
r($designTester->batchCreateTest($ddsDesgins))    && p('0:name,type')       && e('详细设计1,DDS');          // 批量创建详细设计
r($designTester->batchCreateTest($dbdsDesgins))   && p('0:name,type')       && e('数据库设计1,DBDS');       // 批量创建数据库设计
r($designTester->batchCreateTest($adsDesgins))    && p('0:name,type')       && e('接口设计1,ADS');          // 批量创建接口设计
r($designTester->batchCreateTest($noTypeDesgins)) && p('0')                 && e('『设计类型』不能为空。'); // 批量创建无类型设计
r($designTester->batchCreateTest($allDesgins))    && p('0:name,type')       && e('概要设计3,HLDS');         // 批量创建所有类型设计
r($designTester->batchCreateTest($storyDesgins))  && p('0:name,type,story') && e('概要设计4,HLDS,1');       // 批量创建需求关联设计
