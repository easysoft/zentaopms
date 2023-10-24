#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/design.class.php';
su('admin');

/**

title=测试 designModel->batchCreate();
cid=1
pid=1

批量创建设计数量统计 >> 4
批量创建敏捷项目设计 >> 设计一
批量创建瀑布项目设计 >> 32
批量创建看板项目设计 >> 41
不输入类型 >> 『设计类型』不能为空。
不输入名字 >> 0
不输入需求 >> 1
不输入详情 >> 1

*/
$projectIDList = array('12', '32', '62', '13', '71', '72', '73', '75');
$productIDList = array('1', '21', '41');
$storys        = array('1', '2', '3', '5');
$types         = array('HLDS', 'DDS', 'DBDS', 'ADS');
$names         = array('设计一', '设计二', '设计三', '设计四');
$desc          = array('详情一', '详情二', '详情三', '详情四');

$normalDesign  = array('story' => $storys, 'type' => $types, 'name' => $names, 'desc' => $desc);
$noStoryDesign = array('type' => $types, 'name' => $names, 'desc' => $desc);
$noTypeDesign  = array('story' => $storys, 'name' => $names, 'desc' => $desc);
$noNameDesign  = array('story' => $storys, 'type' => $types, 'desc' => $desc);
$noDescDesign  = array('story' => $storys, 'type' => $types, 'name' => $names);

$design = new designTest();
r(count($design->batchCreateTest($projectIDList[7], $productIDList[0], $noDescDesign))) && p()            && e('4');                     //批量创建设计数量统计
r($design->batchCreateTest($projectIDList[0], $productIDList[0], $normalDesign))        && p('0:name')    && e('设计一');                //批量创建敏捷项目设计
r($design->batchCreateTest($projectIDList[1], $productIDList[1], $normalDesign))        && p('0:project') && e('32');                    //批量创建瀑布项目设计
r($design->batchCreateTest($projectIDList[2], $productIDList[2], $normalDesign))        && p('0:product') && e('41');                    //批量创建看板项目设计
r($design->batchCreateTest($projectIDList[4], $productIDList[0], $noTypeDesign))        && p('type:0')    && e('『设计类型』不能为空。');//不输入类型
r($design->batchCreateTest($projectIDList[5], $productIDList[0], $noNameDesign))        && p()            && e('0');                     //不输入名字
$result = $design->batchCreateTest($projectIDList[3], $productIDList[0], $noStoryDesign);
r(empty($result[0]->story))                                                             && p()            && e(1);                       //不输入需求
$result = $design->batchCreateTest($projectIDList[6], $productIDList[0], $noDescDesign);
r(empty($result[0]->desc))                                                              && p('0:desc')    && e(1);                      //不输入详情

