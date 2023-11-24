#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/datatable.class.php';
su('admin');

/**

title=测试 datatableModel::getSetting();
timeout=0
cid=1

- 获取1和2的排序结果 @-1
- 获取2和1的排序结果 @1
- 获取1和1的排序结果 @0
- 获取非正确数组的排序结果 @0

*/
global $tester;
$tester->loadModel('datatable');

$array1 = array('order' => 1);
$array2 = array('order' => 2);
$array3 = array();

r($tester->datatable->sortcols($array1, $array2)) && p() && e('-1');  //获取1和2的排序结果
r($tester->datatable->sortcols($array2, $array1)) && p() && e('1');   //获取2和1的排序结果
r($tester->datatable->sortcols($array1, $array1)) && p() && e('0');   //获取1和1的排序结果
r($tester->datatable->sortcols($array3, $array3)) && p() && e('0');   //获取非正确数组的排序结果
